<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;

use sigc\Group;
use sigc\Concept;
use sigc\Student;
use sigc\Configuration;

use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;


class classroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classrooms = Group::where('classroom_flag',1)->orderby('year','desc')->orderby('identifier')->get();
        foreach ($classrooms as $classroom) {
            if($classroom->id_md5==""){
                $classroom->id_md5 = Hashids::encode($classroom->id+1000);
                $classroom->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->url_base = "classrooms";
        $gc->classrooms = $classrooms;
        $gc->page_name = "Lista de aulas";
        $gc->page_description = "Esta lista contiene la lista de aulas de la IIEE";
        $gc->add_elements = true;
        $gc->breadcrumb('classrooms');

        return view('cms.classrooms.list', compact('gc'));
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
        $gc->url_base = "classrooms";
        $gc->page_name = "Crear nueva aula";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->entity_to_edit = new Group;
        $gc->breadcrumb('classrooms.create');

        return view('cms.classrooms.form', compact('gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        

        $classroom = new Group;        
        $classroom->classroom_flag = 1;
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;

        //Creando el identificador
        $identifier = $request->level * 100;

        switch ($identifier) {
            case '100':                
                $identifier = $identifier + $request->initial_grade*10;
                break;
            case '200':
                $identifier = $identifier + $request->primary_grade*10;
                break;
            case '300':
                $identifier = $identifier + $request->secundary_grade*10;
                break;
            default:
                echo dd("Error al asignar grado");
                break;
        }

        //Generando el identificador del aula
        $classroom_same_grade = Group::withTrashed()->where('classroom_flag',1)->where('identifier','>',$identifier)->where('identifier','<',$identifier+10)->get();
        $number_classroom = count($classroom_same_grade)+1;
        $identifier = $identifier + $number_classroom;        
        $classroom->identifier=$identifier;

        //asignando nombre
        $name_by_infraestructure = "";

        if($request->level==1)
        {
            switch ($request->initial_grade) {
                case '3':
                    $name_by_infraestructure = "Inicial, 3 años ";
                    break;
                case '4':
                    $name_by_infraestructure = "Inicial, 4 años ";
                    break;
                case '5':
                    $name_by_infraestructure = "Inicial, 5 años ";
                    break;                
                default:
                    echo dd("Error al crear nombre por infraestructura, inicial");
                    break;
            }
        }

        if($request->level==2)
        {
            switch ($request->primary_grade) {
                case '1':
                    $name_by_infraestructure = "Primaria, 1er grado ";
                    break;
                case '2':
                    $name_by_infraestructure = "Primaria, 2do grado ";
                    break;
                case '3':
                    $name_by_infraestructure = "Primaria, 3er grado ";
                    break; 
                case '4':
                    $name_by_infraestructure = "Primaria, 4to grado ";
                    break;
                case '5':
                    $name_by_infraestructure = "Primaria, 5to grado ";
                    break;
                case '6':
                    $name_by_infraestructure = "Primaria, 6to grado ";
                    break;                
                default:
                    echo dd("Error al crear nombre por infraestructura, primaria");
                    break;
            }
        }

        if($request->level==3)
        {
            switch ($request->secundary_grade) {
                case '1':
                    $name_by_infraestructure = "Secundaria, 1er grado ";
                    break;
                case '2':
                    $name_by_infraestructure = "Secundaria, 2do grado ";
                    break;
                case '3':
                    $name_by_infraestructure = "Secundaria, 3er grado ";
                    break; 
                case '4':
                    $name_by_infraestructure = "Secundaria, 4to grado ";
                    break;
                case '5':
                    $name_by_infraestructure = "Secundaria, 5to grado ";
                    break;         
                default:
                    echo dd("Error al crear nombre por infraestructura, secundaria");
                    break;
            }
        }

        $name_by_infraestructure = $name_by_infraestructure.chr($number_classroom+64);

        $classroom->description = $name_by_infraestructure;
        
        if($request->name=="")
            $classroom->name = $name_by_infraestructure;

        $config = Configuration::where('current',1)->first();
        $classroom->year = $config->year;
        $classroom->save();

        return Redirect::to('/classrooms');
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
        $classroom = Group::find(Hashids::decode($id)[0]-1000);

        if($classroom==null){
            return Redirect::to('/classrooms');
        }

        $gc = new generalContainer;
        $gc->form = true;
        $gc->page_name = "Editar Aula";
        $gc->description = "Modifique los datos necesarios";
        $gc->select=true;
        $gc->url_base = "classrooms";
        $gc->entity_to_edit=$classroom;
        $gc->breadcrumb('classrooms.edit.'.$classroom->name);

        return view('cms.classrooms.form', compact('gc'));
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
        $classroom = Group::find($id);
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->save();

        return Redirect::to('/classrooms');
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
        $classroom = Group::find(Hashids::decode($id)[0]-1000);
        $classroom->delete();

        return Redirect::to('/classrooms');
    }

    public function trash()
    {
        $classrooms = Group::onlyTrashed()->where('classroom_flag',1)->orderby('identifier')->get();

        foreach ($classrooms as $classroom) {
            if($classroom->id_md5==""){
                $classroom->id_md5 = Hashids::encode($classroom->id+1000);
                $classroom->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->trash = true;
        $gc->url_base = "classrooms";
        $gc->classrooms = $classrooms;
        $gc->page_name = "Lista de Descuentos eliminados";
        $gc->page_description = "Esta lista contiene los descuentos eliminados";
        $gc->breadcrumb('classrooms.trash');

        return view('cms.classrooms.list', compact('gc'));
    }

    public function untrashed($id)
    {
        Group::onlyTrashed()->find(Hashids::decode($id)[0]-1000)->restore();
        return Redirect::to('classrooms/trash/trash');
    }

    public function list_student($id)
    {
        
        $classroom=Group::find(Hashids::decode($id)[0]-1000);
        
        $config = Configuration::where('current',1)->first();

        $students = Student::whereHas('studentxgroupxyear',function($q) use($classroom,$config){
                    $q->where('id_group',$classroom->id);
                })->orderBy('created_at', 'desc')->get();
        
        $gc =  new generalContainer;
        $gc->entity_to_edit = $classroom;
        $gc->students = $students;
        $gc->default_buttons = false;
        $gc->table = true;
        $gc->url_base = 'groups';
        $gc->page_name = "Alumnos del aula: ".$classroom->name;
        $gc->breadcrumb('classrooms.add_list.'.$classroom->id_md5);

        return view('cms.classrooms.add_list', compact('gc'));
    }
}
