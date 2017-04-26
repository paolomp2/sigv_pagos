<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;
use Illuminate\Database\Eloquent\Collection;

use sigc\Bulck;
use sigc\Classroom;
use sigc\Concept;
use sigc\conceptXgroup;
use sigc\conceptxstudent;
use sigc\Configuration;
use sigc\Group;
use sigc\Student;
use sigc\sameStudent;
use sigc\StudentXClassroom;
use sigc\studentXgroupXyear;
use sigc\Ubigeo;
use sigc\Payment_document;

use Auth;
use File;
use DB;
use Redirect;

class reportController extends Controller
{
    public function consolidatedDebtReportGet()
    {
    	//get all classrooms
        $cClassrooms = Group::where("classroom_flag",1)
        					->orderBy("year","desc")
        					->orderBy("identifier","asc")
    						->get();
		$gc = new generalContainer;
		$gc->classrooms = $cClassrooms;

		return view('cms.reports.consolidatedDebtReport', compact('gc'));
    }

    public function consolidatedDebtReport(Request $request)
    {
    	//get all classrooms
        $cClassrooms = Group::where("classroom_flag",1)
        					->orderBy("year","desc")
        					->orderBy("identifier","asc")
    						->get();
		$gc = new generalContainer;
		$gc->classrooms = $cClassrooms;

		//get all Concepts
        $sQuery = "select 
					c.id, c.name
				from
					students s, conceptxstudent cxs, concepts c, groups g, studentxgroupxyear sxgxy
				where
					s.id = cxs.id_student and
					c.id = cxs.id_concept and
					g.id = $request->classroom_id and
					sxgxy.id_group = g.id and
					sxgxy.id_student = s.id
				Group by
					c.id
				Order by
					c.id_concept_group asc,
					c.fecha_vencimiento asc";

        $collection = DB::select(DB::raw($sQuery));

        $gc->concepts = $collection;

		//get all students x concept
		$sQuery = "select 
					CONCAT(s.last_name,' ',s.maiden_name,', ',s.first_name) as fullname, 
					c.id,
					IF(cxs.already_paid=0,cxs.original_amount-cxs.total_paid,0) as debt
				from
					students s, conceptxstudent cxs, concepts c, groups g, studentxgroupxyear sxgxy
				where
					s.id = cxs.id_student and
					c.id = cxs.id_concept and
					g.id = $request->classroom_id and
					sxgxy.id_group = g.id and
					sxgxy.id_student = s.id
				Order by
					fullname,
					c.id_concept_group asc,
					c.fecha_vencimiento asc";

        $collection = DB::select(DB::raw($sQuery));

        $gc->consolidatedDebtReportGrid = $collection;

        return view('cms.reports.consolidatedDebtReport', compact('gc'));
    }

    public function paymentsByDatesReportGet()
    {
    	$gc = new generalContainer;
    	return view('cms.reports.paymentsByDatesReport', compact('gc'));
    }

    public function paymentsByDatesReport(Request $request)
    {
    	$gc = new generalContainer;
    	$gc->dateFrom = $request->dateFrom;
    	$gc->dateTo = $request->dateTo;

    	$gc->payment_documents = Payment_Document::where('date_sell','>=',$request->dateFrom)
    											->where('date_sell','>=',$request->dateTo)
    											->OrderBy('correlative_number')
    											->get();

    	return view('cms.reports.paymentsByDatesReport', compact('gc'));
    }
}