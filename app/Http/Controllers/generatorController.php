<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;

use sigc\Schedule;
use sigc\Concept;
use sigc\Group;
use sigc\conceptxstudent;
use sigc\conceptxgroup;
use sigc\studentxgroupxyear;
use sigc\conceptxinterest;

use sigc\Interest;
use sigc\Discount;

use sigc\Payment_doc;
use sigc\Payment_doc_detail;

use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;

use Carbon\Carbon;

use sigc\Configuration;

use DB;

class generatorController extends Controller
{
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

    /**
    * This function creates all the payment docs in a period
    * @param date $date_ini initial date of the period
    * @param date $date_fin final date of the period
    */
    public function create_docs($year,$amount,$date_ini,$date_end)
    {   
        $date_ini = Carbon::createFromFormat("Y-m-d H:i:s", $date_ini." 00:00:00");
        $date_end = Carbon::createFromFormat("Y-m-d H:i:s", $date_end." 00:00:00");

        $arrayDatesForPay = $this->get_array_prob($date_ini,$date_end);
        $total_days=count($arrayDatesForPay)-1;

        $first_id_payment_doc = -1;
        $last_id_payment_doc = -1;
        //select all students with debs for the year without paid
        $query="select cxe.id,
                        cxe.id_concept,
                        c.name,
                        cxe.id_student,
                        s.first_name,
                        s.middle_name,
                        s.last_name,
                        s.maiden_name,
                        cxe.original_amount,
                        cxe.total_paid,
                        cxe.total_discount,
                        s.prob_procrastination,
                        s.prob_first_place
                from conceptxstudent cxe, concepts c, students s
                where cxe.already_paid = 0
                and cxe.id_concept = c.id
                and cxe.id_student = s.id
                and cxe.already_paid = 0
                and c.year = $year
                and cxe.deleted_at is null
                and c.deleted_at is null
                order by c.fecha_vigencia asc, s.prob_procrastination asc;";

        $table = DB::select(DB::raw($query));
        $remaining_amount = $amount;
        //dd(gettype($table[0]));
        foreach ($table as $key => $row) {
            
            if ($remaining_amount==0) {
                break;
            }

            $payment_doc = new Payment_doc;
            $payment_doc->student_id = $row->id_student;
            $payment_doc->date_sell = $arrayDatesForPay[rand(0,$total_days)];
            $payment_doc->save();

            //get the first id for subsequent verification
            if ($first_id_payment_doc==-1) {
                $first_id_payment_doc=$payment_doc->id;
            }
            $last_id_payment_doc=$payment_doc->id;

            $num_lines = 1;

            $rand = rand(1,100);
            while ($rand>95) {//5% de posibilidades de no pagar más de un concepto a la vez
                $num_lines++;
                $rand = rand(1,100);
            }
            echo "id del documento:  $payment_doc->id <br>";
            echo "id del estudiante: $payment_doc->student_id <br>";
            echo "# de líneas:       $num_lines <br>";

            //Id of the student of document
            $id_student = $row->id_student;
            for ($i=0; $i < $num_lines; $i++) {
                echo "    id Row: ";
                //If the remaining_amount is less than the remaining amount of
                //the relation between the the concept and the student
                //then the document pyment line just take the diference
                $amount_line = 0;
                $remaining_debt_row = $row->original_amount - $row->total_discount - $row->total_paid;

                $flag_conceptxsudent_paid=false;
                echo "$row->id    Monto:";
                if( $remaining_amount < $remaining_debt_row){
                    $amount_line = $remaining_amount;
                }else{
                    $amount_line = $remaining_debt_row;
                    $flag_conceptxsudent_paid=true;
                }
                echo "$amount_line";
                //Updating the total amount for the payment document
                $payment_doc->total_amount += $amount_line;
                $remaining_amount-= $amount_line;

                //Updating the total paid of studentxconcept
                $cxs = conceptxstudent::find($row->id);
                $cxs->total_paid+=$amount_line;
                if ($flag_conceptxsudent_paid) {
                    $cxs->already_paid = 1;
                }
                $cxs->save();

                //registering the line
                $line = new Payment_doc_detail;
                $line->id_payment_document = $payment_doc->id;
                $line->id_conceptxstudent = $row->id;
                $line->amount = $amount_line;
                $line->save();

                /*$num_elements = count($table);
                echo "<br>count before delete: $num_elements<br><pre>";
                print_r($table);*/
                array_forget($table,$key);
                $num_elements = count($table);
                /*echo "</pre>count before delete: $num_elements<pre>";
                print_r($table);
                echo "</pre>";*/

                //if the number of lines is lower than 1 and the remaining amount is 0
                //then this fork should be break

                if ($remaining_amount==0) {
                    break;
                }

                //if more than one line then
                //update the row
                if ($i+1!=$num_lines) {
                    echo "<br>cambiando<br>";
                    $finded = false;
                    foreach ($table as $key_next => $row_next) {
                        if ($row_next->id_student == $id_student) {
                            echo "encontrado<br>";
                            $row = $row_next;
                            $key = $key_next;
                            $finded = true;
                            break;
                        }
                    }
                    if (!$finded) {
                        echo "no encontrado<br>";
                        break;
                    }
                }
                echo "<br><br>";
            }

            $payment_doc->save();
        }

        $query="select sum(pdd.amount) as suma
                from payment_document_details pdd 
                where pdd.id_payment_document >= $first_id_payment_doc and pdd.id_payment_document <= $last_id_payment_doc;";

        $total = DB::select(DB::raw($query));
        $total = $total[0];
        echo "first ID: $first_id_payment_doc <br>";
        echo "last ID: $last_id_payment_doc <br>";
        echo "<p>Total: $total->suma</p>";

    }

    public function get_array_prob($date_beg,$date_end)
    {
        $days = array(
                    '1' => 10, 
                    '2' => 10, 
                    '3' => 10, 
                    '4' => 10, 
                    '5' => 10, 
                    '6' => 10, 
                    '7' => 9, 
                    '8' => 9, 
                    '9' => 8, 
                    '10' => 8, 
                    '11' => 6, 
                    '12' => 6, 
                    '13' => 5, 
                    '14' => 4, 
                    '15' => 3, 
                    '16' => 2, 
                    '17' => 1, 
                    '18' => 1, 
                    '19' => 1, 
                    '20' => 1, 
                    '21' => 1, 
                    '22' => 1,
                    '23' => 1, 
                    '24' => 1,
                    '25' => 1,
                    '26' => 1, 
                    '27' => 1, 
                    '28' => 1, 
                    '29' => 7, 
                    '30' => 9, 
                    '31' => 8
                    );
        
        $holidays = array(  1 => '2014-01-01',
                            2 => '2014-04-17',
                            3 => '2014-04-18',
                            4 => '2014-04-20',
                            5 => '2014-05-01',
                            6 => '2014-06-24',
                            7 => '2014-06-29',
                            8 => '2014-07-28',
                            9 => '2014-07-29',
                            10 => '2014-08-30',
                            11 => '2014-10-08',
                            12 => '2014-11-01',
                            13 => '2014-12-08',
                            14 => '2014-12-25',
                            15 => '2015-01-01',
                            16 => '2015-01-02',
                            17 => '2015-04-02',
                            18 => '2015-04-03',
                            19 => '2015-04-05',
                            20 => '2015-05-01',
                            44 => '2015-06-24',
                            21 => '2015-06-29',
                            22 => '2015-07-27',
                            23 => '2015-07-28',
                            24 => '2015-07-29',
                            25 => '2015-08-30',
                            26 => '2015-10-08',
                            27 => '2015-11-01',
                            28 => '2015-12-08',
                            29 => '2015-12-25',
                            30 => '2016-01-01',
                            31 => '2016-01-02',
                            32 => '2016-03-24',
                            33 => '2016-03-25',
                            34 => '2016-06-24',
                            35 => '2016-06-29',
                            36 => '2016-07-28',
                            37 => '2016-07-29',
                            38 => '2016-08-29',
                            39 => '2016-08-30',
                            40 => '2016-10-08',
                            41 => '2016-11-01',
                            42 => '2016-12-08',
                            43 => '2016-12-25'
                            );

        $arrayDatesStringFormat = array();

        $day_running = $date_beg;        

        while ($day_running<$date_end) {
            
            //excluding weekend
            if ($day_running->dayOfWeek == 0 || $day_running->dayOfWeek == 6){
                $day_running->addDay();
                continue;
            }

            $day_running_string = $day_running->toDateString();

            //echo "$day_running_string";
            //dd(array_search($day_running_string, $holidays));
            if(array_search($day_running_string, $holidays)==false){
                $times_to_repeat = $days[$day_running->day];
                for ($i=0; $i < $times_to_repeat ; $i++) {
                    array_push($arrayDatesStringFormat, $day_running_string);
                }
            }/*else{
                echo "$day_running_string <br>";
            }*/

            $day_running->addDay();
        }

        return $arrayDatesStringFormat;
    }
}

