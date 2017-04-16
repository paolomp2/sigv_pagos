<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;

use sigc\Http\Containers\configurationContainer;
use sigc\Http\Containers\bulckContainer;

use sigc\Http\Libraries\CropAvatar;

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

use Image;
use Vinkla\Hashids\Facades\Hashids;
use Auth;
use File;
use DB;
use Redirect;
use Maatwebsite\Excel\Facades\Excel;

class studentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::orderBy('enrolled_flag','desc')
                            ->orderBy('identifier','asc')
                            ->orderBy('last_name','asc')
                            ->orderBy('maiden_name','asc')
                            ->orderBy('first_name','asc')
                            ->get();

        $gc = new generalContainer;
        $gc->students = $students;

        return view('cms.students.list', compact('gc'));
    }

    public function all_students()
    {
        $students = Student::where('comparison_verified',1)->get();

        foreach ($students as $student) {
            if($student->id_md5==""){
                $student->id_md5 = Hashids::encode($student->id+1000);
                $student->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->url_base = "students";
        $gc->students = $students;
        $gc->page_name = "Lista de alumnos";
        $gc->page_description = "Esta lista contiene la lista de grupos de alumnos";
        $gc->breadcrumb('all_students');
        return view('cms.students.list', compact('gc'));
    }

    public function bulcked_students()
    {
        $students = Student::where('id_bulcks_excel','<>',0)->where('comparison_verified',1)->get();

        foreach ($students as $student) {
            if($student->id_md5==""){
                $student->id_md5 = Hashids::encode($student->id+1000);
                $student->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->url_base = "students";
        $gc->students = $students;
        $gc->page_name = "Lista de alumnos importados";
        $gc->page_description = "Esta lista contiene la lista de grupos de alumnos";
        $gc->breadcrumb('bulcked_students');

        return view('cms.students.list', compact('gc'));
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
        $gc->url_base = "students";
        $gc->page_name = "Crear nuevo estudiante";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->entity_to_edit = new Student;
        $gc->entity_to_edit->creating_flag = 1;
        $gc->entity_to_edit->save();
        $gc->ubigeos = Ubigeo::all();
        $gc->breadcrumb('students.create');

        return view('cms.students.form', compact('gc'));
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
        $student = Student::find($request->student_id);
        
        $student->first_name = $request->names;
        $student->last_name = $request->last_name;
        $student->maiden_name = $request->maiden_name;

        $student->gender = $request->gender;
        $student->dni = str_replace("-","",$request->dni);
        $student->ubigeo_id = $request->ubigeo;
        $student->address = $request->address;
        $student->cellphone = str_replace("-","",$request->cellphone);
        $student->phone = str_replace("-","",$request->phone);
        $student->birthday = $request->date_birthday;
        $student->creating_flag = 0;

        $student->id_md5 = Hashids::encode($student->id+1000);
        $student->save();

        return Redirect::to('/students');
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
        $student = Student::find(Hashids::decode($id)[0]-1000);

        $gc = new generalContainer;
        $gc->page_name = "Editar Alumno";
        $gc->description = "Modifique los datos necesarios";
        $gc->form = true;
        
        $gc->picture = true;
        $gc->date = true;
        $gc->select = true;
        $gc->url_base = "students";
        $gc->entity_to_edit=$student;
        //echo dd($student);
        $gc->ubigeos = Ubigeo::all();
        $gc->breadcrumb('students.edit.'.$student->name);

        return view('cms.students.form', compact('gc'));
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
        $student = Student::find($id);
        
        $student->first_name = $request->names;
        $student->last_name = $request->last_name;
        $student->maiden_name = $request->maiden_name;
        $student->gender = $request->gender;
        $student->dni = str_replace("-","",$request->dni);
        $student->ubigeo_id = $request->ubigeo;
        $student->address = $request->address;
        $student->cellphone = str_replace("-","",$request->cellphone);
        $student->phone = str_replace("-","",$request->phone);
        $student->birthday = $request->date_birthday;

        $student->save();

        return Redirect::to('/students');
    }

    public function enrolling_fast()
    {
        $gc = new generalContainer;
        $gc->page_name = "Matrícula rápida";
        $gc->page_description = "<h3> 1. Registre uno o más alumnos.</h3>";
        $gc->page_description_2 = "<h3> 2. Seleccione un aula y agrege alumnos..</h3>";
        $gc->form = true;
        $gc->select = true;
        $gc->url_base = "students";
        $gc->classrooms = Group::where('classroom_flag',1)->get();
        $gc->select = true;
        $gc->breadcrumb('enrolling_fast');
        return view('cms.students.enrolling_fast', compact('gc'));
    }

    public function add_student(Request $request)
    {
        $gc = new generalContainer;
        $gc->page_name = "Selección de alumnos";
        $gc->page_description = "Seleccione un alumno y de click en agregar";
        $gc->form = true;
        $gc->select = true;
        $gc->url_base = "students";        
        $gc->entity_to_edit = Group::find($request->classroom);
        //echo dd($gc->entity_to_edit);
        $gc->students = Student::all();
        //$gc->students = Student::where('enrolled_flag',0)->get();
        $gc->select = true;
        $gc->breadcrumb('enrolling_fast.add_student');
        return view('cms.students.enrolling_fast_add', compact('gc'));
    }

    public function add_student_store(Request $request)
    {

        $iId_Classroom = $request->classroom_id;
        $config = Configuration::where('current',1)->first();
        //first the student will be added to a classroom
        $id_md5_students = preg_split("`,`", $request->studients_ids);
        $iId_students=-1;
        for ($i=1; $i < count($id_md5_students); $i++) {
            //UPDATE IDENTIFIER ON STUDENT
            $iId_students = Hashids::decode($id_md5_students[$i])[0]-1000;
            $student = Student::find($iId_students);
            $student->enrolled_flag = 1;
            $student->identifier = $request->identifier;
            $student->year = $config->year;
            $student->save();
            //UPDATE RELATHION BETWEEN STUDENT AND GROUP
            $relationClassroom = new studentXgroupXyear;
            $relationClassroom->id_group = $iId_Classroom;
            $relationClassroom->id_student = $iId_students;
            $relationClassroom->classroom_flag = 1;
            $relationClassroom->save();
            //UPDATE CONCEPTXSTUDENT
            $oSchedule = new scheduleController;
            $oSchedule->applyConceptsToStudentUsingGroup($iId_students,$iId_Classroom);

            $classroom = Group::find($iId_Classroom);
            $classroom->num_people = $classroom->num_people + 1;
            $classroom->save();
        }

        $gc = new generalContainer;
        $gc->page_name = "Resultados";
        $gc->page_description = "De click en siguiente para agregar más alumnos.";
        $gc->num_students = count($id_md5_students)-1;
        $gc->breadcrumb('enrolling_fast.add_student_store');

        return view('cms.students.enrolling_fast_resoult', compact('gc'));
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
              '/public/images/students/',
              $request->student_id
            );

            //echo dd($request);
            return response()->json([
                    'state'  => 200,
                    'message' => $crop -> getMsg(),
                    'result' => $base_name .'/images/students/'. $request->student_id.'.png'
                ]);
        }
    }

    public function bulck_load(Request $request){
        
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

        $gc = new generalContainer;
        $gc->groups = Group::where('classroom_flag',1)->orderBy('year')->orderBy('name')->get();
        return view('cms.students.bulck_update', compact('gc','cc'));

    }

    public function bulck_store(Request $request){

        $mClassroom = Group::find($request->selected_year);
        /*store Image*/
        $file_Name = 'excel.'.$request->file('file_excel')->getClientOriginalExtension();
        $request->file('file_excel')->move(
            base_path() . '/public/', $file_Name
        );

        //echo dd($file_Name);

        //cargar todas las hojas
            //según el nombre de la hoja, cargar o crear aula
                //Para cada fila
                    //cargar cada nombre de alumno
                    //hacer split al nombre
                    //las 2 primeras palabras será considerados apellidos

        //global  $b_c;
        $bulck = new Bulck;
        $bulck->type = 1; //Type 1 = Students
        $bulck->status = 1; //Initializing
        $bulck->file_name = $request->file('file_excel')->getClientOriginalName();
        $bulck->year = $mClassroom->year;
        $bulck->save();

        $b_c = new bulckContainer;
        $b_c->id = $bulck->id;
        $b_c->file_name = $request->file('file_excel')->getClientOriginalName();

        $excel=Excel::load($file_Name, function($reader) use($b_c, $request, $bulck,$mClassroom) {

            //creando array auxiliar
            $aux_dates = array();
            //Por cada hoja
            foreach($reader->get() as $sheet)
            {
                $b_c->num_students_aux=0;
                
                $sheet->each(function($row) use($b_c, $mClassroom, $bulck) {

                    //Tope de lectura
                    //si la siguiente linea esta vacía, se retorna
                    /*if($row->get('name')==""){
                        return;
                    }*/

                    $student = new Student;

                    $b_c->num_students++;
                    $b_c->num_students_aux++;
                    $student->full_name = $row->get('name');
                    $student_name_array = explode(" ",$student->full_name);                   
                    

                    if (count($student_name_array)>0) {
                        $student->last_name = $student_name_array[0];
                    }
                    
                    if (count($student_name_array)>1) {
                        $student->maiden_name = $student_name_array[1];
                    }

                    if (count($student_name_array)>2) {
                        $student->first_name = $student_name_array[2];
                    }

                    if (count($student_name_array)>3) {
                        $student->middle_name = $student_name_array[3];
                    }

                    
                    $student->id_bulcks_excel = $bulck->id;
                    $student->identifier = $mClassroom->identifier;
                    
                    $configuration = Configuration::where('year',$mClassroom->year)->first();
                    
                    $student->enrolled_flag = 1;  
                    $student->year = $configuration->year;
                    $student->comparison_verified = 0;
                    $student->save();
                    $student->id_md5 = Hashids::encode($student->id+1000);
                    $student->save();

                    $relationship = new studentXgroupXyear;
                    $relationship->id_group = $mClassroom->id;
                    $relationship->id_student = $student->id;
                    $relationship->save();

                    $mClassroom->num_people = $mClassroom->num_people + 1;
                    $mClassroom->save();

                    //adding concept
                    $cConceptxgroup = conceptXgroup::where('id_group',$mClassroom->id)->get();
                    foreach ($cConceptxgroup as $mconceptXgroup) {
                        $mConcept = concept::find($mconceptXgroup->id_concept);
                        if(is_null($mConcept))
                            continue;
                        $conceptxstudent = new conceptxstudent();
                        $conceptxstudent->id_concept = $mConcept->id;
                        $conceptxstudent->original_amount = $mConcept->amount;
                        $conceptxstudent->id_student = $student->id;
                        $conceptxstudent->save();
                    }
                });
                break;             
            }

        })->get();

        //Actualizando el estado de la carga
        $bulck->status = 2;
        $bulck->save();
        
        $gc = new generalContainer;

        return view('cms.students.bulck_resoult', compact('gc'));

    }

    public function bulck_search_repeted(Request $request){

        //Seleccionar todos los alumnos que tuvieron un bulck
        //Seleccionar todos los bulcks que ya finalizaron (estado 3)
        //comparar cada nombre con cada apellido
        //Mostrar una tabla
        //Configuration
        $config = Configuration::where('current',1)->first();
        //Seleccionar todos los alumnos que tuvieron un bulck
        $students_to_compare = Student::where('id_bulcks_excel',$request->bulck_id)->get();
        //Seleccionar todos los bulcks que ya finalizaron (estado 3)
        $bulcks_finalized = Bulck::where('status',3)->get();
        $students_stored = Student::where('comparison_verified',1)->get();        

        //comparar cada nombre con cada apellido
        foreach ($students_to_compare as $student_to_compare) {

            $same_surnames = $students_stored->where('last_name', $student_to_compare->last_name)->where('maiden_name', $student_to_compare->maiden_name);

            if(count($same_surnames)>0){


                $same_first_name = $same_surnames->where('first_name', $student_to_compare->first_name);
                if (count($same_first_name)>0) {

                    foreach ($same_first_name as $student_finded) {
                        $same_studentes =  new sameStudent();
                        $same_studentes->id_bulck =  $request->bulck_id;
                        $same_studentes->id_student_verified = $student_finded->id;
                        $same_studentes->id_student_new = $student_to_compare->id;

                        if($student_finded->middle_name == $student_to_compare->middle_name)
                            $same_studentes->probability_of_similarity = 100;
                        else
                            $same_studentes->probability_of_similarity = 75;     
                        
                        $same_studentes->save();
                    }
                }
            }

        }

        //Primero actualizo todos los estudiantes que sean iguales y los unifico
        //finalmente elimino el registro del estudiante repetido
        $same_Students = sameStudent::where('id_bulck',$request->bulck_id)->where('probability_of_similarity',100)->get();
        $num_students_proccess = count($same_Students);
        //echo dd($same_Students);
        foreach ($same_Students as $same_Student) {
            //Selecciono las aulas en un anho en el que el estudiante trata de matricularse
            $StudentXClassrooms_sameStudent = studentXgroupXyear::where('id_student',$same_Student->id_student_new)->get();

            foreach ($StudentXClassrooms_sameStudent as $StudentXClassroom_sameStudent) {
                //por cada aula verifico que no exista ya la id del alumno verificado en esa aula
                //es decir, verifico que el alumno verificado (el que voy a insertar en el aula)
                //no haya sido ya registrado

                $classroomXyear = studentXgroupXyear::where('id_student',$same_Student->id_student_verified)->
                                                    where('year',$StudentXClassroom_sameStudent->year)->
                                                    where('id_group',$StudentXClassroom_sameStudent->id_group)->
                                                    get();
                //En caso no exista, reemplazo
                if(count($classroomXyear)==0){
                    DB::table('studentxgroupxyear')
                        ->where('id_student', $StudentXClassroom_sameStudent->id_student)
                        ->where('id_group', $StudentXClassroom_sameStudent->id_group)
                        ->where('year', $StudentXClassroom_sameStudent->year)
                        ->update(['id_student' => $same_Student->id_student_verified]);
                }else{
                    DB::table('studentxgroupxyear')
                        ->where('id_student', $StudentXClassroom_sameStudent->id_student)
                        ->where('id_group', $StudentXClassroom_sameStudent->id_group)
                        ->where('year', $StudentXClassroom_sameStudent->year)
                        ->delete();
                }
            }
            //echo dd($same_Student->id_student_new);            
            $student_verified = Student::find($same_Student->id_student_verified);
            $student_to_delet = Student::find($same_Student->id_student_new);
            if($student_to_delet->year > $student_verified->year)
            {
                $student_verified->year = $student_to_delet->year;
                $student_verified->classroom_id = $student_to_delet->classroom_id;
                $student_verified->enrolled_flag = $student_to_delet->enrolled_flag;
                $student_verified->save();
            }
            $student_to_delet->forceDelete();
        }

        //Actualizo el flag para corroborar que los alumnos sean permanentes
        $same_Students = Student::where('id_bulcks_excel',$request->bulck_id)->update(['comparison_verified' => 1]);;
        $gc = new generalContainer;
        $gc->create = true;
        $gc->select = true;
        $gc->date = true;
        $gc->page_name = "Resultados de la comparación de usuarios";
        $gc->page_description = "Click en retornar para iniciar una nueva carga masiva de alumnos";        

        $b_c = new bulckContainer;
        $b_c->num_students_aux = $num_students_proccess;

        //Relaciona alumnos con conceptos
        $schedulle = new scheduleController;
        $bulck = Bulck::find($request->bulck_id);

        $concepts = Concept::where("year",$bulck->year)->get();
        foreach ($concepts as $concept) {
            $schedulle->apply_concept($concept->id);
        }
        
        return view('cms.students.resoult', compact('gc','b_c'));

    }
}
