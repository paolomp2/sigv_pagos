<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;

use sigc\Student;
use sigc\Concept_group;
use sigc\Concept;
use sigc\Discount;
use sigc\Interest;
use sigc\Group;
use sigc\Configuration;
use sigc\discountXgroup;
use sigc\interestXgroup;
use sigc\conceptxdiscount;
use sigc\conceptxinterest;
use sigc\conceptxstudent;
use sigc\Payment_document;
use sigc\Payment_document_group;
use sigc\Payment_document_line;

use Vinkla\Hashids\Facades\Hashids;
use Auth;
use DB;
use Redirect;
use File;
use Carbon\Carbon;


class paymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showListStudents()
    {
        $gc = new generalContainer;
        $gc->students = Student::all();
        return view('cms.payment.showStudents', compact('gc'));
    }

    public function showDebts($id_student)
    {
        $gc = new generalContainer;
        //get all conceptxstudent
        //for each concept get all discounts
            //get all 
        $iId_student=Hashids::decode($id_student)[0]-1000;
        $gc->entity_to_edit = Student::find($iId_student);

        $gc->concepts = $this->getConceptsbyStudentID($iId_student);
        $cDiscountxStudents = $this->getDiscountsByStudentOrderByConcept($iId_student);

        return view('cms.payment.selectConceptsToPay', compact('gc','cDiscountxStudents'));

    }
    
    public function showReceiptConsole(Request $request){        
        $iId_student = $request->id_student;
        $gc = new generalContainer;
        $iAmountToPay = $request->amountToPay;

        $gc->entity_to_edit = Student::find($iId_student);
        $gc->concepts = $this->getConceptsbyStudentID($iId_student);
        $cDiscountxStudents = $this->getDiscountsByStudentOrderByConcept($iId_student);
        return view('cms.payment.receiptConsole', compact('gc','cDiscountxStudents','iAmountToPay'));        
    }

    public function makePayment(Request $request)
    {
        $iId_student = $request->id_student;
        $iAmountToPay = explode('.', $request->amountToPay)[1] ;
        $iTotalDiscountXConcept = 0;
        $iAmountToPayXConcept = 0;
        $iFlagAlreadyPaid = 0;
        $iPayment_document_total_to_pay = 0;
        $cConcepts = $this->getConceptsbyStudentID($iId_student);
        $cDiscountxStudents = $this->getDiscountsByStudentOrderByConcept($iId_student);
        $iLastIdDiscount = -1;
        
        //create Payment Document
        $mPayment_document_group = payment_document_group::find(1);
        $mpayment_document = new payment_document();
        $mpayment_document->id_student = $iId_student;
        $mpayment_document->status = config('CONSTANTS.CREATED');
        $mpayment_document->id_document = $mPayment_document_group->id;
        $mpayment_document->correlative_number = $mPayment_document_group->current_correlative_number;
        $mpayment_document->date_sell = Carbon::now();
        $mpayment_document->save();
        $mpayment_document->id_md5 = Hashids::encode($mpayment_document->id+1000);
        $mpayment_document->save();

        foreach ($cConcepts as $iIndexConcept => $oConcepts) {
            
            $mpayment_document_line_document = new payment_document_line();
            $mpayment_document_line_document->id_entity = $oConcepts->id;
            $mpayment_document_line_document->expiration_date = $oConcepts->fecha_vencimiento;
            $mpayment_document_line_document->id_document_payment = $mpayment_document->id;
            $mpayment_document_line_document->save();

            $iTotalDiscountXConcept = 0;
            $iFlagAlreadyPaid = 0;
            $cPayment_document_line_to_pay = array();
            foreach ($cDiscountxStudents as $iIndexDiscount => $oDiscountxStudents) {
                if ($oDiscountxStudents->id_concept==$oConcepts->id && $iLastIdDiscount != $oDiscountxStudents->id_discount) {
                    $iTotalDiscountXConcept+=$oDiscountxStudents->amount;
                    $iLastIdDiscount = $oDiscountxStudents->id_discount;

                    $mpayment_document_line = new payment_document_line();
                    $mpayment_document_line->type_entity = 'DISCOUNT';
                    $mpayment_document_line->id_entity = $oDiscountxStudents->id_discount;
                    $mpayment_document_line->amount = $oDiscountxStudents->amount;
                    $mpayment_document_line->expiration_date = $oConcepts->fecha_vencimiento;
                    $mpayment_document_line->id_document_payment = $mpayment_document->id;
                    array_push($cPayment_document_line_to_pay, $mpayment_document_line);                    
                }                
            }
            $iAmountToPay+=$iTotalDiscountXConcept;
            
            $oConcepts->amount -= $oConcepts->total_paid;
            if($iAmountToPay>=$oConcepts->amount)
            {                
                $iAmountToPayXConcept = $oConcepts->amount;
                $iAmountToPay -= $oConcepts->amount;
                $iFlagAlreadyPaid = 1;

                //IF THE TOTAL AMOUNT TO PAY IS MORE THAN THE DIFF BETWEEN THE CONCEPT AMOUNT AND THE SUM OF DISCOUNT
                //THEN I CAN APPLY THE DISCOUNT
                foreach ($cPayment_document_line_to_pay as $mpayment_document_line) {
                   $mpayment_document_line->save();
                }
                
            }else{
                $iAmountToPay-=$iTotalDiscountXConcept;
                $iTotalDiscountXConcept = 0;
                $iAmountToPayXConcept = $iAmountToPay;
                $iAmountToPay = 0;
                
            }
            $iPayment_document_total_to_pay+=$iAmountToPayXConcept-$iTotalDiscountXConcept;
            
            $mpayment_document_line_document->amount = $iAmountToPayXConcept;
            $mpayment_document_line_document->save();
            //Update conceptXstudent

            $mConceptXstudent = conceptxstudent::where('id_concept',$oConcepts->id)
                                ->where('id_student',$iId_student)
                                ->where('already_paid',0)
                                ->first();

            $mConceptXstudent->total_paid = $iAmountToPayXConcept;
            $mConceptXstudent->total_discount = $iTotalDiscountXConcept;
            $mConceptXstudent->already_paid = $iFlagAlreadyPaid;
            $mConceptXstudent->save();
        }
        //UPDATTING THE TOTAL AMOUNT
        $mpayment_document->status = config('CONSTANTS.PAID_OUT');
        $mpayment_document->total_amount = $iPayment_document_total_to_pay;
        $mpayment_document->save();
        //UPDATING THE CORRELATIVE NUMBER
        $mPayment_document_group->current_correlative_number++;
        $mPayment_document_group->save();

        return Redirect::to('/printPaymentDocument/'.$mpayment_document->id_md5);
    }



    public function getConceptsByStudentID($iId_student)
    {
        $sQuery = "select
                        c.*,
                        cxs.total_paid
                    from
                        concepts as c,
                        conceptxstudent cxs
                    where
                        cxs.id_student = $iId_student and
                        c.id = cxs.id_concept and
                        cxs.already_paid = 0 and
                        c.deleted_at is null and
                        cxs.deleted_at is null
                    order By c.fecha_vencimiento ASC";
        return DB::select(DB::raw($sQuery));
    }

    public function getDiscountsByStudentOrderByConcept($iId_student)
    {
        $sQuery = "select 
                      c.id as id_concept,
                       d.id as id_discount,
                       dxg.id_group,
                       d.id_md5, 
                       d.name, 
                       if(d.percentage_flag=0, d.amount, c.amount*d.amount/100) as amount,
                       DATE_ADD(c.fecha_vencimiento, INTERVAL d.days_after_expiration_date DAY) as expiration_date
                 from
                     discounts d, concepts c, discountxgroup dxg, conceptxgroup cxg
                 where
                     d.id_concept_group = c.id_concept_group and
                     d.id = dxg.id_discount and
                     dxg.id_group in(
                         select id_group
                         from studentxgroupxyear
                         where id_student = $iId_student
                     ) and
                     cxg.id_concept = c.id and
                     dxg.id_group = cxg.id_group and
                     d.deleted_at is null and
                     c.deleted_at is null and
                     dxg.deleted_at is null and
                     cxg.deleted_at is null and
                    DATE_ADD(c.fecha_vencimiento, INTERVAL d.days_after_expiration_date DAY) >= now()
                 order by c.year desc, c.id asc, d.name asc, d.id asc";

                 return DB::select(DB::raw($sQuery));
    }

    public function printPaymentDocument($id_payment_document_md5){
        
        $id_payment_document = Hashids::decode($id_payment_document_md5)[0]-1000;
        $oPayment_document= payment_document::find($id_payment_document);
        $cPayment_document_line = payment_document_line::where('id_document_payment',$oPayment_document->id)->get();

        $data =  [
            'quantity'      => '1' ,
            'description'   => 'some ramdom text',
            'price'   => '500',
            'total'     => '500'
        ];
        $date = date('Y-m-d');
        $invoice = "2222";

        //Print PDF
        $view =  \View::make('cms.payment.documentToPrint', compact('data', 'date', 'invoice','oPayment_document','cPayment_document_line'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->download('payment_'.$id_payment_document_md5.'.pdf');
    }

    public function printPaymentDocument_test($id_payment_document_md5)
    {
        $id_payment_document = Hashids::decode($id_payment_document_md5)[0]-1000;
        $oPayment_document= payment_document::find($id_payment_document);
        $cPayment_document_line = payment_document_line::where('id_document_payment',$oPayment_document->id)->get();
        
        //Print PDF
        $view =  \View::make('cms.payment.documentToPrint', compact('oPayment_document','cPayment_document_line'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('payment_'.$id_payment_document_md5.'.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}