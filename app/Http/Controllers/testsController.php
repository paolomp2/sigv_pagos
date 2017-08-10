<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Controllers\scheduleController;

use sigc\Schedule;
use sigc\Concept;
use sigc\Group;
use sigc\conceptxstudent;
use sigc\conceptxgroup;
use sigc\studentxgroupxyear;
use sigc\conceptxinterest;
use sigc\Student;
use sigc\Interest;
use sigc\Discount;


use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;

use Carbon\Carbon;

use sigc\Configuration;

use DB;

class testsController extends Controller
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

    public function createStudents($total_students)
    {
        for ($i=1; $i+1 < $total_students; $i++) {
            
            $student = new Student;
            $student->first_name = "first_name ".$i;
            $student->middle_name = "middle_name ".$i;
            $student->last_name = "last_name ".$i;
            $student->maiden_name = "maiden_name ".$i;
            $student->year = 2016;
            $student->save();

            $student->id_md5 = Hashids::encode($student->id+1000);
            $student->save();
        }
    }

    public function createGroupConcepts($num_groups_concept,$min_concepts_by_groups_concept,$max_concepts_by_groups_concept)
    {
        
    }

    public function white($value='')
    {
        

        $students = Student::all();
        $oSchedule = new scheduleController;
        foreach ($students as $student) {
            $oSchedule->refresh_debts_students($student->id);
        }
    }
}
