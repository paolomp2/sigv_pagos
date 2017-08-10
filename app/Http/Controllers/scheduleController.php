<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;

use sigc\Schedule;
use sigc\Concept;
use sigc\Group;
use sigc\conceptxstudent;
use sigc\conceptXgroup;
use sigc\studentXgroupXyear;
use sigc\conceptxinterest;

use sigc\Interest;
use sigc\Discount;

use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;

use Carbon\Carbon;

use sigc\Configuration;

use DB;

class scheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $gc = new generalContainer;
        $gc->schedules = Schedule::all(); 
        /*if(is_null($gc->schedules))
            $gc->schedules=array()*/

        foreach ($gc->schedules as $schedule) {
            if($schedule->id_md5==""){
                $schedule->id_md5 = Hashids::encode($schedule->id+1000);
                $schedule->save();
            }
        }
        $gc->url_base = "schedules";
        $gc->table = true;
        $gc->page_name = "Lista de Cronogramas";
        $gc->page_description = "Esta lista contiene los cronogramas de pagos";
        $gc->breadcrumb('schedules');

        return view('cms.schedules.list', compact('gc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gc = new generalContainer;
        $gc->create = true;
        $gc->select = true;
        $gc->form = true;
        $gc->url_base = "schedules";
        $gc->page_name = "Crear nuevo Cronograma";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->entity_to_edit = new Schedule;
        //$gc->breadcrumb('schedules.create');

        return view('cms.schedules.form', compact('gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $schedule = new Schedule;
        $schedule->name = $request->name;
        $schedule->description = $request->description;
            
        $schedule->save();

        return Redirect::to('/schedules');
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
    * Excecute a thread that verify the time and recompute the debt for each student
    * This function use the concepts, interest and discount activated for activate the amount
    */

    public function execute_schedule()
    {
        //get current year
        //get flag schedulle
        //if the flag is false
            //calculate the time remaining until midnight
            //sleep the time remaining until midnight
            //recompute for each concept activated recompute()

        $congif = Configuration::where("current",1)->first();
        if($congif->flag_calculate_shedulle==0)
        {
            $congif->flag_calculate_shedulle = 1;
            $congif->save();
            //calculate the time remaining until midnight
            $current_time= Carbon::now('America/Lima');
            $tomorrow = Carbon::tomorrow('America/Lima');
            $remaining_time = $tomorrow->diffInSeconds($current_time);
            //sleep the time remaining until midnight
            sleep($remaining_time);
            $this->recompute();
        }
    }

    public function recompute()
    {

        $now = Carbon::now('America/Lima');
        //string for years to procces

        //select all interest for update the amount to apply over the students
        $relations = conceptxinterest::where("expiration_date","<",$now)
                                        ->orderBy("concept_id","Desc")
                                        ->orderBy("interest_id","Desc")
                                        ->get();
        $concepts = Concept::all();
        $interests = Interest::all();
        $debs = conceptxstudent::orderBy("id_concept","Desc")
                                ->orderBy("id_student","Desc")
                                ->get();

        //We'll proccess the relations andd update the amount for each debs of students
        foreach ($relations as $relation) {

            $concept = $concepts->where("id",$relation->concept_id)->first();
            $interest = $interests->where("id",$relation->interest_id)->first();

            $query = "select DISTINCT studentxgroupxyear.id_student
                        from studentxgroupxyear
                        where 
                        studentxgroupxyear.deleted_at is null and
                        studentxgroupxyear.id_group in (
                            select conceptxgroup.id_group 
                            from conceptxgroup 
                            where
                            conceptxgroup.id_concept = $relation->concept_id and
                            conceptxgroup.deleted_at is null and
                            conceptxgroup.id_group in (
                                select interestxgroup.id_group 
                                from interestxgroup
                                where id_interest = $relation->interest_id and
                                interestxgroup.deleted_at is null
                            ) and
                        studentxgroupxyear.id_student in(
                            select conceptxstudent.id_student
                            from conceptxstudent
                            where (original_amount - total_discount + total_interest) < total_paid
                        )
                    )";

            /*if($relation->interest_id==14 && $relation->concept_id==2)
            echo $query.";<p>";*/

            $students = DB::select(DB::raw($query));

            if (count($students)!=0) {
                //update interest for every student registered

                dd($students);
            }
            

        }
        //dd("fin");
        //select all discounts for update the amount to apply over the students


        $due_date = Carbon::createFromFormat('Y-m-d', $concept->fecha_vencimiento,'America/Lima');
    }

    public function apply_concept($concept_id)
    {
        //select all groups to apply
        //for each group apply the concept
        $conceptsxgroups = conceptXgroup::where("id_concept",$concept_id)->get();
        foreach ($conceptsxgroups as $conceptxgroup) {
            $this->apply_concept_to_group($concept_id, $conceptxgroup->id_group);
        }

    }

    public function apply_concept_to_group($id_concept, $group_id)
    {
        //Get concept, group and year to apply
        $concept = Concept::find($id_concept);
        $group = Group::find($group_id);

        $students_group = studentXgroupXyear::where("id_group",$group->id)
                                            ->select(array('id_student'))
                                            ->get();

        $students_registered = conceptxstudent::withTrashed()
                                                ->where("id_concept",$id_concept)
                                                ->get();

        //Apply the concept to all students in the groups
        //if the student exist in the list of conceptsXstudents then no apply the rule
        foreach ($students_group as $student) {
            
            $student_registered = $students_registered->where("id_student",$student->id_student)->first();
            
            if (!is_null($student_registered)) {
                //case restore student
                if($student_registered->deleted_at!=null)
                    $student_registered->deleted_at=null;
                //case update concept
                //Case insert new group but the student have a relationship with the concetp
                $student_registered->original_amount=$concept->amount;
                $student_registered->total_groups = $student_registered->total_groups+1;
                $student_registered->save();
            }else{
                $new_debt = new conceptxstudent;
                $new_debt->id_concept=$id_concept;
                $new_debt->id_student=$student->id_student;
                $new_debt->original_amount=$concept->amount;
                $new_debt->total_groups = 1;
                $new_debt->save();
            }
        }
    }
    /***
    *
    */
    public function applyConceptsToStudentUsingGroup($iId_student,$iId_group){
        
        //Get all concepts related with the group
        $cConceptXGroup = conceptxgroup::where('id_group', $iId_group)->get();

        foreach ($cConceptXGroup as $mConceptXGroup) {
            $mConcept = Concept::find($mConceptXGroup->id_concept);
            
            if ($mConcept->fecha_vigencia<Carbon::now('America/Lima')) {
                continue;
            }

            //find if the relationship exits
            //if exist update total_group
            //else create the relation
            $cConceptxstudent = conceptxstudent::where('id_student',$iId_student)
                                                ->where('id_concept',$mConcept->id)
                                                ->get();

            if(count($cConceptxstudent)>0){
                $cConceptxstudent[0]->total_groups = $cConceptxstudent[0]->total_groups + 1;
                $cConceptxstudent[0]->save();
            }else{
                $oconceptxstudent = new conceptxstudent;
                $oconceptxstudent->id_student = $iId_student;
                $oconceptxstudent->id_concept = $mConcept->id;
                $oconceptxstudent->total_groups = 1;
                $oconceptxstudent->original_amount = $mConcept->amount;
                $oconceptxstudent->save();
            }            
        }
    }

    public function delete_group_in_concept($id_concept, $id_group)
    {
        //decrease the number of groups in 1
        $query="Update conceptxstudent 
                set total_groups = total_groups - 1
                where id_concept = $id_concept and
                id_student in (
                    select id_student
                    from studentxgroupxyear
                    where id_group = $id_group
                )";
        $students = DB::select(DB::raw($query));

        //delete all relation where the number of group is zero
        $conceptsXstudents = conceptxstudent::where("id_concept",$id_concept)
                                            ->where("total_groups",0)
                                            ->delete();
    }

    public function apply_discount_to_group($id_discount,$id_group,$flag_enrollment_remove)
    {
        $oConfiguration = Configuration::where("current",1)->first();
        $oDiscount = Discount::find($id_discount);
        $sAmountToDiscount = "";

        if ($oDiscount->percentage_flag==1) {//Case Percentage
            $sAmountToDiscount = "cxs.original_amount * $oDiscount->amount / 100 ";
        }else{//Case solid amount
            $sAmountToDiscount = "$oDiscount->amount";
        }

        if ($flag_enrollment_remove) {
            $sAmountToDiscount = "cxs.total_discount - ".$sAmountToDiscount;
        }else{
            $sAmountToDiscount = "cxs.total_discount + ".$sAmountToDiscount;
        }


        $query="update
                    conceptxstudent cxs 
                set
                    cxs.total_discount = $sAmountToDiscount
                where
                    cxs.id_student in (
                        select
                            sxg.id_student
                        from
                            studentxgroupxyear sxg, discountxgroup dxg
                        where   
                            sxg.id_group = $id_group and
                            sxg.year = $oConfiguration->year and
                            dxg.id_group = sxg.id_group and
                            sxg.deleted_at is null and
                            dxg.deleted_at is null
                    ) and
                    cxs.deleted_at is null";
        dd($query);
         $rs = DB::select(DB::raw($query));

         foreach ($rs as $r) {
            $conceptxstudent = $conceptsxstudents->where("id_concept",$r->id_concept)
                                                    ->where("id_student",$r->id_student)
                                                    ->first();

            if(is_null($conceptxstudent))
            {
                dd($r);
            }
            if($flag_enrollment_remove)
                $conceptxstudent->total_discount = $conceptxstudent->total_discount + $r->discount;
            else
                $conceptxstudent->total_discount = $conceptxstudent->total_discount - $r->discount;
            $conceptxstudent->save();
         }

    }

    public function apply_discount_to_all_groups($id_discount,$flag_enrollment_remove)
    {
        $this->apply_discount_to_group($id_discount,0,$flag_enrollment_remove);
    }

    public function refresh_debts_students($iId_student)
    {
        $sQuery = "update conceptxstudent cxs
                    set deleted_at = now()
                    where
                        cxs.id_student = $iId_student and
                        cxs.id_concept not in(
                            select 
                                cxg.id_concept
                            from
                                conceptxgroup cxg
                            where
                                cxg.deleted_at is null and
                                cxg.id_group in(
                                    select
                                        sxgxy.id_group
                                    from
                                        studentxgroupxyear sxgxy
                                    where
                                        sxgxy.id_student = $iId_student and
                                        sxgxy.deleted_at is null
                                )   
                        )";

        return DB::select(DB::raw($sQuery));
    }
}
