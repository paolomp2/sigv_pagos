<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;

use sigc\Http\Libraries\CropAvatar;

use sigc\Configuration;
use sigc\Classroom;
use sigc\Student;
use sigc\Family_member;
use sigc\StudentXClassroom;
use sigc\Ubigeo;
use sigc\Group;
use sigc\Relationship;
use sigc\studentXfamily_member;

use Image;

use Vinkla\Hashids\Facades\Hashids;
use Auth;
use File;
use DB;
use Redirect;

use Maatwebsite\Excel\Facades\Excel;

class family_memberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $families_members = Family_member::where('visible_flag',1)->get();
        
        $gc = new generalContainer;
        $gc->families_members = $families_members;
        
        return view('cms.family_members.list', compact('gc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gc = new generalContainer;
        $gc->form = true;
        $gc->create = true;        
        $gc->picture = true;
        $gc->date = true;
        $gc->select = true;
        $gc->url_base = "family_members";
        $gc->page_name = "Crear nuevo Familiar";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->entity_to_edit = new Family_member;
        $gc->entity_to_edit->save();
        $gc->ubigeos = Ubigeo::all();
        $gc->breadcrumb('family_members.create');

        return view('cms.family_members.form', compact('gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo dd($request);
        $family_member = Family_member::find($request->family_member_id);

        $names = $request->names;
        $names_array = preg_split("` `", $names);

        $family_member->first_name = $names_array[0];

        $middle_name="";
        for ($i=1; $i < count($names_array) ; $i++) { 
            $middle_name = $middle_name." ".$names_array[1];
        }
        $family_member->middle_name = $middle_name;

        $family_member->last_name = $request->last_name;
        $family_member->maiden_name = $request->maiden_name;
        $family_member->gender = $request->gender;
        $family_member->dni = str_replace("-","",$request->dni);
        $family_member->ubigeo_id = $request->ubigeo;
        $family_member->address = $request->address;
        $family_member->cellphone = str_replace("-","",$request->cellphone);
        $family_member->phone = str_replace("-","",$request->phone);
        $family_member->birthday = $request->date_birthday;
        $family_member->visible_flag = 1;
        $family_member->id_md5 = Hashids::encode($family_member->id+1000);
        $family_member->save();

        return Redirect::to('/family_members');
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
        $family_member = Family_member::find(Hashids::decode($id)[0]-1000);

        $gc = new generalContainer;
        $gc->page_name = "Editar familiar de estudiante";
        $gc->description = "Modifique los datos necesarios";
        $gc->form = true;
        
        $gc->picture = true;
        $gc->date = true;
        $gc->select = true;
        $gc->url_base = "family_members";
        $gc->entity_to_edit=$family_member;
        //echo dd($student);
        $gc->ubigeos = Ubigeo::all();
        $gc->breadcrumb('family_members.edit.');

        return view('cms.family_members.form', compact('gc'));
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
        //echo dd($request);
        $family_member = Family_member::find($id);
        
        $names = $request->names;
        $names_array = preg_split("` `", $names);

        $family_member->first_name = $names_array[0];

        $middle_name="";
        for ($i=1; $i < count($names_array) ; $i++) {
            if($i==1)
                $middle_name = $names_array[$i];
            else
                $middle_name = $middle_name." ".$names_array[$i];
        }
        $family_member->middle_name = $middle_name;

        $family_member->last_name = $request->last_name;
        $family_member->maiden_name = $request->maiden_name;
        $family_member->gender = $request->gender;
        $family_member->dni = str_replace("-","",$request->dni);
        $family_member->ubigeo_id = $request->ubigeo;
        $family_member->address = $request->address;
        $family_member->cellphone = str_replace("-","",$request->cellphone);
        $family_member->phone = str_replace("-","",$request->phone);
        $family_member->birthday = $request->date_birthday;

        $family_member->save();

        return Redirect::to('/family_members');
    }

    public function add_list($id)
    {
        $family_member = Family_member::find(Hashids::decode($id)[0]-1000);       

        $gc = new generalContainer;
        $gc->table = true;
        $gc->url_base = "family_members";
        $gc->page_name = "Lista de estudiantes relacionados a: ".$family_member->first_name." ".$family_member->middle_name.", ".$family_member->last_name." ".$family_member->maiden_name;
        $gc->page_description = "Esta lista contiene la lista de estudiantes relacionados a un familiar";
        $gc->entity_to_edit = $family_member;
        $gc->studentsXfamily_members = studentXfamily_member::where('id_family_member', $family_member->id)->get();
        $gc->msg_add_elements = "Lista de alumnos relacionados";
        $gc->default_buttons = false;
        $gc->add_buttons = true;
        $gc->breadcrumb('family_members.add_list');
        return view('cms.family_members.list_students', compact('gc'));
    }

    public function add($id)
    {
        $gc = new generalContainer;        
        $gc->page_description = "Seleccione un alumno y se agregará automáticamente a la lista, luego de click en Registrar todos los alumnos.";
        $gc->form = true;
        $gc->select = true;
        $gc->url_base = "family_members";        
        $gc->entity_to_edit = Family_member::find(Hashids::decode($id)[0]-1000);
        $gc->page_name = "Relacionar alumnos a: ".$gc->entity_to_edit->first_name." ".$gc->entity_to_edit->middle_name.", ".$gc->entity_to_edit->last_name." ".$gc->entity_to_edit->maiden_name;
        //echo dd($gc->entity_to_edit);
        $gc->students = Student::where('enrolled_flag',1)->get();
        
        $gc->select = true;
        $gc->relationships = Relationship::all();
        $gc->breadcrumb('students.add');


        return view('cms.family_members.add', compact('gc'));
    }

    public function add_store(Request $request)
    {
        $config = Configuration::where('current',1)->first();
        
        //first the student will be added to a classroom
        $id_md5_students = preg_split("`,`", $request->studients_ids);
        $id_students=array();
        $id_relationship=array();

        for ($i=1; $i < count($id_md5_students); $i=$i+2) {
            
            $id_students[$i-1]= Hashids::decode($id_md5_students[$i])[0]-1000;
            $id_relationship[$i-1]= $id_md5_students[$i+1];            

            $relation = new studentXfamily_member;
            $relation->id_student = $id_students[$i-1];
            $relation->id_relationship = $id_relationship[$i-1];
            $relation->id_family_member = $request->family_member_id;
            $relation->save();
            $relation->id_md5 =  Hashids::encode($relation->id+1000);
            $relation->save();
        }

        $family_member = Family_member::find($request->family_member_id);
        $family_member->num_students = $family_member->num_students + (count($id_md5_students)-1)/2;
        $family_member->save();

        return Redirect::to('/family_members/'.$family_member->id_md5.'/list/');
    }    

    public function add_inactive($id)
    {
        $studentXfamily_member = studentXfamily_member::find(Hashids::decode($id)[0]-1000);
        if($studentXfamily_member!=null){
            $family_member = $studentXfamily_member->family_member;
            //echo dd($family_member);
            $family_member->num_students=$family_member->num_students-1;
            $family_member->save();

            $id = $family_member->id_family_member;
            $studentXfamily_member->delete();


            return Redirect::to('/family_members/'.$studentXfamily_member->id.'/list/');
        }else{
            return Redirect::to('/family_members/');
        }        
    }



    public function picture(Request $request)
    {
        if ($request->ajax()) {
            
            //Get url base
            $base_name_array = preg_split("`/`", $request->url());
            //echo dd($base_name_array);
            $base_name = $base_name_array[0]."//".$base_name_array[2];
            //echo dd($base_name);
            
            //proccess the image
            $crop = new CropAvatar(
              isset($request->avatar_src) ? $request->avatar_src : null,
              isset($request->avatar_data) ? $request->avatar_data : null,
              isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null,
              '/public/images/families-members/',
              $request->family_member_id
            );

            //echo dd($request);
            return response()->json([
                    'state'  => 200,
                    'message' => $crop -> getMsg(),
                    'result' => $base_name .'/images/families-members/'. $request->family_member_id.'.png'
                ]);
        }
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
        $family_members = Family_member::find(Hashids::decode($id)[0]-1000);
        $family_members->delete();

        return Redirect::to('/family_members');
    }

    public function trash()
    {
        $families_members = Family_member::onlyTrashed()->where('visible_flag',1)->get();
        
        $gc = new generalContainer;
        $gc->table = true;
        $gc->trash = true;
        $gc->url_base = "family_members";
        $gc->families_members = $families_members;
        $gc->page_name = "Lista de familiares de estudiantes eliminados";
        $gc->page_description = "Esta lista contiene la lista de familiares de estudiantes";
        $gc->add_elements = true;
        $gc->msg_add_elements = "Agregar Alumnos";
        $gc->breadcrumb('family_members.trash');
        return view('cms.family_members.list', compact('gc'));
    }

    public function untrashed($id)
    {
        Family_member::onlyTrashed()->find(Hashids::decode($id)[0]-1000)->restore();
        return Redirect::to('family_members/trash/trash');
    }

}
