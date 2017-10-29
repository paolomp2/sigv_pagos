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
use Vinkla\Hashids\Facades\Hashids;

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
        $concepts = null;
        $ClassRoom_name = "";
		return view('cms.reports.consolidatedDebtReport', compact('cClassrooms','concepts','ClassRoom_name'));
    }

    public function consolidatedDebtReport(Request $request)
    {
    	//get all classrooms
        $cClassrooms = Group::where("classroom_flag",1)
        					->orderBy("year","desc")
        					->orderBy("identifier","asc")
    						->get();

		//get all Concepts
        $sQuery =   "
                    select 
                        c.id, c.name, c.fecha_vencimiento
                    from
                        concepts c
                    left join conceptxstudent cxs on
                        c.id = cxs.id_concept
                    left join studentxgroupxyear sxg on
                        cxs.id_student = sxg.id_student
                    where
                        sxg.id_group = $request->classroom_id 
                    GROUP BY
                        c.fecha_vencimiento,
                        c.name
                    ORDER BY
                        c.fecha_vencimiento, c.name, c.id
                    ";

        $concepts = DB::select(DB::raw($sQuery));
        

		//get all students x concept
		$sQuery = "select 
					s.full_name as fullname, 
					c.id,
					IF(cxs.already_paid=0,cxs.original_amount-cxs.total_paid,0) as debt
				from
					students s, conceptxstudent cxs, concepts c, groups g, studentxgroupxyear sxgxy
				where
					s.id = cxs.id_student and
					c.id = cxs.id_concept and
					g.id = $request->classroom_id and
					sxgxy.id_group = g.id and
					sxgxy.id_student = s.id and
                    c.deleted_at is null and
                    s.deleted_at is null and
                    cxs.deleted_at is null and
                    g.deleted_at is null and
                    sxgxy.deleted_at is null
				Order by
					fullname,
					c.fecha_vencimiento, c.name, c.id";

        $consolidatedDebtReportGrid = DB::select(DB::raw($sQuery));

        $ClassRoom_name = ": ".Group::find($request->classroom_id)->name;
        //dd($consolidatedDebtReportGrid);

        return view('cms.reports.consolidatedDebtReport', compact('cClassrooms','concepts','consolidatedDebtReportGrid','ClassRoom_name'));
    }

    public function paymentsByDatesReportGet()
    {
    	$gc = new generalContainer;
        $dtMinDate = DB::table('payment_document')->min('date_sell');
        $dtMaxDate = DB::table('payment_document')->max('date_sell');
        $config = Configuration::where('current',1)->first();        
        $gc->students = Student::where("year",$config->year)->get();
    	return view('cms.reports.paymentsByDatesReport', compact('gc', 'dtMinDate', 'dtMaxDate'));
    }

    public function paymentsByDatesReport(Request $request)
    {   
        
        $iId_student=$request->student;
    	$gc = new generalContainer;
    	$gc->dateFrom = $request->dateFrom;
    	$gc->dateTo = $request->dateTo;
        $gc->table = true;
        $config = Configuration::where('current',1)->first();        
        $gc->students = Student::where("year",$config->year)->get();

        $sWhereStudent = "";

        if($iId_student > 0){
            $sWhereStudent = "pd.id_student = $iId_student and";
        };

        $sQuery =   "
                        select 
                            pd.*, s.full_name
                        from
                            payment_document pd
                            left join students s on
                                pd.id_student = s.id
                        where                            
                            pd.date_sell >= '$request->dateFrom' and
                            pd.date_sell <= '$request->dateTo' and
                            pd.deleted_at is null and
                            $sWhereStudent
                            1 = 1
                    ";
    	$gc->payment_documents = DB::select(DB::raw($sQuery));           
        
        $dtMinDate = $request->dateFrom;;
        $dtMaxDate = $request->dateTo;

    	return view('cms.reports.paymentsByDatesReport', compact('gc', 'dtMinDate', 'dtMaxDate'));
    }
}
