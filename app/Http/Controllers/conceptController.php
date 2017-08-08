<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;
use sigc\Http\Containers\conceptContainer;

use sigc\Concept_group;
use sigc\Concept;
use sigc\Group;
use sigc\Configuration;
use sigc\conceptXgroup;
use sigc\conceptxdiscount;
use sigc\conceptxinterest;
use sigc\conceptxstudent;
use sigc\Discount;
use sigc\Interest;
use sigc\Schedule;
use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Carbon\Carbon;

use Redirect;

use sigc\Http\Controllers\scheduleController;

class conceptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $concepts = Concept::orderBy("year","Desc")->orderBy("id_concept_group","Asc")->orderBy("fecha_vencimiento","Asc")->get();        
        $gc = new generalContainer;
        $gc->url_base = "concepts";;
        $gc->concepts = $concepts;
        return view('cms.concepts.list', compact('gc','cc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gc = new generalContainer;
        $gc->entity_to_edit = new Concept;
        $gc->entity_to_edit->id_concepto_grupo = -1;
        $gc->create = true;
        $gc->select = true;
        $gc->date = true;
        $gc->url_base = "concepts";
        $gc->page_name = "Crear nuevo Concepto";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->form = true;
        $gc->breadcrumb('concepts.create');
        $cc = new conceptContainer;
        $cc->concept_groups = Concept_group::orderBy("year","Desc")->orderBy("name","Asc")->get();

        return view('cms.concepts.form', compact('gc','cc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $concept = new Concept;
        $concept->name = $request->name;
        $concept->amount = $request->amount;
        $concept->fecha_vigencia = $request->date_release;
        $concept->fecha_vencimiento = $request->date_expiration;
        $concept->id_concept_group = $request->select_concept_group;
        $concept->year = Concept_group::find($request->select_concept_group)->year;
        $concept->save();
        $concept->id_md5 = Hashids::encode($concept->id+1000);
        $concept->save();

        //Apply concept to groups of students
        $schedulle = new scheduleController;
        $schedulle->apply_concept($concept->id);

        return Redirect::to('/concepts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        echo "show";
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concept = Concept::find(Hashids::decode($id)[0]-1000);

        $cc = new conceptContainer;
        $cc->id =    $concept->id;
        $cc->name =    $concept->name;
        $cc->amount =    $concept->amount;
        $cc->concept_groups = Concept_group::all();
        $cc->fecha_vigencia = $concept->fecha_vigencia;
        $cc->fecha_vencimiento = $concept->fecha_vencimiento;
        $cc->year = $concept->year;
        
        $gc = new generalContainer;
        $gc->url_base = "concepts";
        $gc->page_name = "Editar conceptos";
        $gc->description = "Modifique los datos necesarios";
        $gc->select=true;
        $gc->date=true;
        $gc->groups_students = Group::all();
        $gc->form = true;
        $gc->breadcrumb('concepts.edit.'.$concept->name);
        $gc->entity_to_edit = $cc;
        return view('cms.concepts.form', compact('gc','cc'));
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
        $concept = Concept::find($id);
        $concept->name = $request->name;
        $concept->amount = $request->amount;
        $concept->fecha_vigencia = $request->date_release;
        $concept->fecha_vencimiento = $request->date_expiration;
        $concept->id_concept_group = $request->select_concept_group;
        $concept->save();

        //update the amount of relation between the student and the concept
        $cConceptXstudent = conceptxstudent::where('id_concept',$concept->id)
                                                ->where('already_paid',0)
                                                ->get();

        foreach ($cConceptXstudent as $mConceptXstudent) {
            $mConceptXstudent->original_amount = $concept->amount;
            $mConceptXstudent->save();
        }

        return Redirect::to('/concepts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo("destroy");
    }

    public function inactive($id)
    {
        $concept_id = Hashids::decode($id)[0]-1000;
        $concept = Concept::find($concept_id);
        $concept->delete();

        //Destroy concepts x interest
        //conceptxinterest::where("concept_id",$concept_id)->delete();
        //Destroy concepts x discount
        //conceptxdiscount::where("concept_id",$concept_id)->delete();
        //Destroy the relation between the concept and the student
        conceptxstudent::where("id_concept", $concept->id)->delete();
        return Redirect::to('/concepts');
    }

    public function trash()
    {
        $concepts = Concept::onlyTrashed()->get();        
        
        $cc = new conceptContainer;
        $cc->concepts = $concepts;
        

        foreach ($cc->concepts as $concept_group) {
            if($concept_group->id_md5==""){
                $concept_group->id_md5 = Hashids::encode($concept_group->id+1000);
                $concept_group->save();
            }
        }

        $gc = new generalContainer;
        $gc->url_base = "concepts";
        $gc->trash = true;
        $gc->table = true;
        $gc->page_name = "Lista de Conceptos desactivados";
        $gc->page_description = "Esta lista contiene conceptos desactivados";
        $gc->breadcrumb('concepts.trash');
        return view('cms.concepts.list', compact('gc','cc'));
    }

    public function untrashed($id)
    {
        $concept_id = Hashids::decode($id)[0]-1000;
        Concept::onlyTrashed()->find($concept_id)->restore();
        conceptxdiscount::onlyTrashed()->where("concept_id",$concept_id)->restore();
        conceptxinterest::onlyTrashed()->where("concept_id",$concept_id)->restore();
        conceptxstudent::onlyTrashed()->where("id_concept",$concept_id)->restore();
        return Redirect::to('concepts/trash/trash');
    }

    public function list_groups($id)
    {
        $concept = Concept::find(Hashids::decode($id)[0]-1000);
        if(is_null($concept))
        {
            return Redirect::to('concepts/');
        }

        $gc = new generalContainer;        
        $gc->entity_to_edit = $concept;
        $gc->groups = Group::whereHas('conceptXgroup', function($q) use($concept){
                                    $q->where('id_concept', $concept->id);
                                })->get();
        //echo dd($gc->groups);
        $gc->url_base="concepts";
        return view('cms.concepts.list_add', compact('gc'));
    }

    public function add_elements($id)
    {
        $concept = Concept::find(Hashids::decode($id)[0]-1000);
        if(is_null($concept))
        {
            return Redirect::to('concepts/');
        }

        $gc = new generalContainer;
        $gc->trash = true;
        $gc->table = true;
        $gc->select = true;
        $gc->page_name = "Concepto: ".$concept->name;
        $gc->page_description = "Seleccione los grupos y luego de click en confirmar";
        $gc->url_base="concepts";
        $gc->entity_to_edit = $concept;
        $gc->groups_students = Group::where('year', $concept->year)->orderBy('identifier','description')->get();        

        return view('cms.concepts.push_add', compact('gc'));
    }

    public function add_store(Request $request)
    {
        $iIdGroup=-1;
        $iIdConcept = $request->entity_to_edit_id;
        $config = Configuration::where('current',1)->first();
        //first the student will be added to a classroom
        $aIdMd5Group = preg_split("`,`", $request->elements_id);        
        
        
        for ($i=1; $i < count($aIdMd5Group); $i++) {

            $iIdGroup= Hashids::decode($aIdMd5Group[$i])[0]-1000;            

            $relation = conceptXgroup::withTrashed()
                                    ->where('id_concept',$iIdConcept)
                                    ->where('id_group',$iIdGroup)                                        
                                    ->first();

            if(is_null($relation))
            {
                $relation = new conceptXgroup;
                $relation->id_concept = $iIdConcept;
                $relation->id_group = $iIdGroup;
                $relation->save();
                
            }else{
                $relation = conceptXgroup::where('id_concept',$iIdConcept)
                                        ->where('id_group',$iIdGroup)
                                        ->restore();
            }
            //Update concepts for a group of students
            $oSchedule = new scheduleController;
            $oSchedule->apply_concept_to_group($iIdConcept, $iIdGroup);            
        }


        return Redirect::to('concepts/'.Hashids::encode($iIdConcept+1000).'/add');
    }
    

    public function add_inactive($id_concept, $id_group)
    {
        $id_concept_dec = Hashids::decode($id_concept)[0]-1000;
        $id_group_dec = Hashids::decode($id_group)[0]-1000;

        $schedulle = new scheduleController;
        $schedulle->delete_group_in_concept($id_concept_dec, $id_group_dec);

        conceptXgroup::where('id_concept',$id_concept_dec)
                    ->where('id_group',$id_group_dec)
                    ->delete();

        return Redirect::to('concepts/'.$id_concept.'/add');
    }
}
