<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;
use Illuminate\Database\Eloquent\Collection;

use sigc\Group;
use sigc\studentxgroupxyear;
use sigc\Student;
use sigc\Configuration;

use Vinkla\Hashids\Facades\Hashids;
use Auth;
use DB;
use Redirect;


class groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::where('classroom_flag',0)->where('identifier',0)->get();

        foreach ($groups as $group) {
            if($group->id_md5==""){
                $group->id_md5 = Hashids::encode($group->id+1000);
                $group->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->add_elements = true;
        $gc->msg_add_elements = "Agregar alumnos";
        $gc->url_base = "groups";
        $gc->groups = $groups;
        $gc->page_name = "Lista de grupos de alumnos`";
        $gc->page_description = "Esta lista contiene la lista de grupos de alumnos";
        $gc->breadcrumb('groups');
        return view('cms.groups.list', compact('gc'));
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
        $gc->url_base = "groups";
        $gc->page_name = "Crear nuevo grupo de alumnos";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->entity_to_edit = new Group;
        $gc->breadcrumb('groups.create');
        return view('cms.groups.form', compact('gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $config = Configuration::where("current",1)->first();

        $group = new Group;
        $group->name = $request->name;
        $group->description = $request->description;    
        $group->year = $config->year;
        $group->save();

        return Redirect::to('/groups');
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
        $group = Group::find(Hashids::decode($id)[0]-1000);

        $gc = new generalContainer;
        $gc->page_name = "Editar Descuento";
        $gc->description = "Modifique los datos necesarios";
        $gc->select=true;
        $gc->url_base = "groups";
        $gc->entity_to_edit=$group;
        $gc->form = true;
        $gc->breadcrumb('groups.edit.'.$gc->entity_to_edit->name);
        return view('cms.groups.form', compact('gc'));
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
        $hroup = Group::find($id);
        $hroup->name = $request->name;
        $hroup->description = $request->description;
        $hroup->save();

        return Redirect::to('/groups');
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

    public function inactive($id)
    {
        $discount = Group::find(Hashids::decode($id)[0]-1000);
        $discount->delete();

        return Redirect::to('/groups');
    }

    public function trash()
    {
        $groups = Group::onlyTrashed()->where('classroom_flag',0)->get();

        foreach ($groups as $group) {
            if($group->id_md5==""){
                $group->id_md5 = Hashids::encode($group->id+1000);
                $group->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->trash = true;
        $gc->url_base = "groups";
        $gc->groups = $groups;
        $gc->page_name = "Lista de grupos de alumnos eliminados";
        $gc->page_description = "Esta lista contiene la lista de grupos de alumnos eliminados";
        $gc->breadcrumb('groups.trash');
        return view('cms.groups.list', compact('gc'));
    }

    public function untrashed($id)
    {
        Group::onlyTrashed()->find(Hashids::decode($id)[0]-1000)->restore();
        return Redirect::to('groups/trash/trash');
    }

    public function list_groups($id)
    {
        $group=Group::find(Hashids::decode($id)[0]-1000);
        $config = Configuration::where('current',1)->first();

        $students = Student::whereHas('studentxgroupxyear',function($q) use($config,$group){
                    $q->where('year',$config->year)->where('id_group',$group->id);
                })->orderBy('created_at', 'desc')->get();
        
        $gc =  new generalContainer;
        $gc->entity_to_edit = $group;
        $gc->students = $students;
        $gc->default_buttons = false;
        $gc->add_buttons =true;
        $gc->table = true;
        $gc->url_base = 'groups';
        $gc->page_name = "Alumnos del grupo: ".$group->name;
        $gc->breadcrumb('groups.list_groups.'.$group->id_md5);

        return view('cms.groups.add_list', compact('gc'));
    }

    public function add_inactive($id_group, $id_student)
    {

        $id_group_dec = Hashids::decode($id_group)[0]-1000;
        $id_student = Hashids::decode($id_student)[0]-1000;

        $pivot = studentxgroupxyear::where('id_group',$id_group_dec)->where('id_student',$id_student)->first();
        $pivot->delete();

        $group = Group::find($id_group_dec);
        //Caso en que eliminamos un alumno de un grupo que es aula
        if($group->classroom_flag==1)
        {
            $student = Student::find($id_student);
            $student->enrolled_flag = 0;
            $student->save();
        }

        return Redirect::to('/groups/'.$id_group.'/add');
    }

    public function add_elements($id_group)
    {
        $group=Group::find(Hashids::decode($id_group)[0]-1000);
        $gc =  new generalContainer;
        $gc->entity_to_edit = $group;
        $gc->default_buttons = false;
        $gc->url_base = 'groups';
        $gc->page_name = "Insertar alumnos al grupo: ".$group->name;
        $gc->page_description = "1. Seleccione esta opción para insertar alumnos";
        $gc->page_description_2 = "2. Seleccione esta opción para copiar otros grupos";
        $gc->breadcrumb('groups.add_list.'.$group->id_md5.'.add_pre.'.$group->id_md5);

        return view('cms.groups.add_pre_insert', compact('gc'));
    }

    public function add_elements_students($id_group)
    {
        $group=Group::find(Hashids::decode($id_group)[0]-1000);
        $gc =  new generalContainer;
        $gc->entity_to_edit = $group;
        $gc->table = true;
        $gc->select = true;
        $gc->default_buttons = false;
        $gc->url_base = 'groups';
        $gc->page_name = "Insertar alumnos al grupo: ".$group->name;
        $gc->page_description = "Seleccione los alumnos que necesite agregar al grupo";
        $gc->breadcrumb('groups.add_list.'.$group->id_md5.'.add_store.'.$group->id_md5);

        $config = Configuration::where("current",1)->first();

        $sQuery = "select * from students
                    where deleted_at is null 
                        and creating_flag = 0 
                        and enrolled_flag = 1
                        and year = $config->year
                        and (select count(*) 
                                from studentxgroupxyear 
                                where studentxgroupxyear.id_student = students.id
                                    and studentxgroupxyear.id_group = $group->id  and studentxgroupxyear.deleted_at is null ) < 1";
        
        $gc->students = DB::select($sQuery);
        return view('cms.groups.add_element', compact('gc'));
    }

    public function add_store_students(Request $request)
    {
        $iId_students=-1;
        $iId_group = $request->id_group;
        $config = Configuration::where('current',1)->first();
        //first the student will be added to a classroom
        $aiId_student = preg_split("`,`", $request->studients_ids);
        
        for ($i=1; $i < count($aiId_student); $i++) {

            $iId_students = Hashids::decode($aiId_student[$i])[0]-1000;

            $relationClassroom = new studentXgroupXyear;
            $relationClassroom->id_group = $iId_group;
            $relationClassroom->id_student = $iId_students;
            $relationClassroom->save();

            $group = Group::find($iId_group);
            $group->num_people = $group->num_people + 1;
            $group->save();

            //UPDATE CONCEPTXSTUDENT
            $oSchedule = new scheduleController;
            $oSchedule->applyConceptsToStudentUsingGroup($iId_students,$iId_group);
        }

        return Redirect::to('/groups/'.Hashids::encode($request->id_group+1000).'/add');
    }

    public function add_elements_groups($id_group)
    {
        $group=Group::find(Hashids::decode($id_group)[0]-1000);
        $gc =  new generalContainer;
        $gc->entity_to_edit = $group;
        $gc->table = true;
        $gc->select = true;
        $gc->default_buttons = false;
        $gc->url_base = 'groups';
        $gc->page_name = "Insertar alumnos al grupo: ".$group->name;
        $gc->page_description = "Seleccione los alumnos que necesite agregar al grupo";
        $gc->breadcrumb('groups.add_list.'.$group->id_md5.'.add_store.'.$group->id_md5);

        /*$gc->students = DB::table('Students')
                        ->where('comparison_verified',1)->where('creating_flag',0)
                        ->leftJoin('studentxgroupxyear','Students.id','=','studentxgroupxyear.id_student')
                        ->where('studentxgroupxyear.id_group',$group->id)
                        ->toSql();
        echo dd($gc->students);*/
        
        $gc->students = DB::select('select * from `students` 
                            where `students`.`deleted_at` is null 
                                and `comparison_verified` = 1 
                                and `creating_flag` = 0 
                                and `enrolled_flag` = 1 
                                and (select count(*) 
                                        from `studentxgroupxyear` 
                                        where `studentxgroupxyear`.`id_student` = `students`.`id`
                                            and `studentxgroupxyear`.`id_group` = '.$group->id.' ) < 1');
        //echo dd($gc->students);
        return view('cms.groups.add_element', compact('gc'));
    }
}
