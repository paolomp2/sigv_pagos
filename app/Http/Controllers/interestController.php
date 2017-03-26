<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;

use sigc\Concept_group;
use sigc\Concept;
use sigc\Interest;
use sigc\Group;
use sigc\Configuration;
use sigc\interestXgroup;
use sigc\conceptxinterest;

use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;

class interestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interests = Interest::all();

        foreach ($interests as $discount) {
            if($discount->id_md5==""){
                $discount->id_md5 = Hashids::encode($discount->id+1000);
                $discount->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->url_base = "interests";
        $gc->interests = $interests;
        $gc->page_name = "Lista de Intereses";
        $gc->page_description = "Esta lista contiene los intereses aplicados a los conceptos de pago";
        $gc->breadcrumb('interests');

        return view('cms.interests.list', compact('gc'));
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
        $gc->page_name = "Crear nuevo Interés";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->url_base = "interests";
        $gc->entity_to_edit = new Interest;
        $gc->entity_to_edit->days_after_expiration_date=0;
        $gc->concepts_groups = Concept_group::all();
        $gc->breadcrumb('interests.create');
        return view('cms.interests.form', compact('gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $interest = new Interest;
        $interest->name = $request->name;
        $interest->amount = $request->amount;
        $interest->percentage_flag = $request->radio_button_porcentage;
        $interest->recurrence = $request->recurrence;
        $interest->num_times = $request->num_times;
        $interest->concept_group = $request->select_concept_group;
        $interest->save();

        //select all concept and add interest to concept
        $concepts = Concept::where("id_concepto_grupo",$request->select_concept_group)->get();

        foreach ($concepts as $concept) {
            $conceptxinterest = new conceptxinterest;
            $conceptxinterest->concept_id = $concept->id;
            $conceptxinterest->expiration_date = $concept->fecha_vencimiento;
            $conceptxinterest->interest_id = $interest->id;
            $conceptxinterest->save();
        }

        return Redirect::to('/interests');
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

        $gc = new generalContainer;
        $gc->select = true;
        $gc->form = true;
        $gc->page_name = "Editar Interés";
        $gc->page_description = "Modifique los campos requeridos";
        $gc->url_base = "interests";
        $gc->entity_to_edit = Interest::find(Hashids::decode($id)[0]-1000);
        $gc->concepts_groups = Concept_group::all();        
        $gc->concepts_groups_id = $gc->entity_to_edit->Concepts_groups()->getRelatedIds()->toArray();
        /*echo dd($gc->concepts_groups_id->toArray());*/

        /*foreach ($gc->concepts_groups as $concept_group) {
            $selected = false;
              if($gc->concepts_groups_id!=null)
                $selected = array_search($concept_group->id,$gc->concepts_groups_id);

            if ($selected>-1) {
                echo '-'.$selected.'-<br>';
            }
            
        }
        echo dd("Stop");*/
        
        

        $gc->discounts = Interest::all();
        $gc->breadcrumb('interests.edit.'.$gc->entity_to_edit->name);
        return view('cms.interests.form', compact('gc'));

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
        $ineterest = Interest::find($id);
        $ineterest->name = $request->name;
        $ineterest->amount = $request->amount;
        $ineterest->percentage_flag = $request->radio_button_porcentage;
        $ineterest->recurrence = $request->recurrence;
        $ineterest->num_times = $request->num_times;
        $ineterest->save();

        $ineterest->Concepts_groups()->sync([$request->select_concept_group]);

        return Redirect::to('/interests');
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

    public function trash()
    {
        $interests = Interest::onlyTrashed()->get();

        foreach ($interests as $interest) {
            if($interest->id_md5==""){
                $interest->id_md5 = Hashids::encode($interest->id+1000);
                $interest->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->trash = true;
        $gc->url_base = "interests";
        $gc->interests = $interests;
        $gc->page_name = "Lista de Intereses eliminados";
        $gc->page_description = "Esta lista contiene los intereses eliminados";
        $gc->breadcrumb('interests.trash');

        return view('cms.interests.list', compact('gc'));
    }

    public function inactive($id)
    {
        $interest_id = Hashids::decode($id)[0]-1000;
        $interest = Interest::find($interest_id);
        $interest->delete();

        $interestsxconcept = conceptxinterest::where("interest_id",$interest_id)->get();
        foreach ($interestsxconcept as $interestxconcept) {
            $interestxconcept->delete();
        }

        return Redirect::to('/interests');
    }

    public function untrashed($id)
    {
        $interest_id = Hashids::decode($id)[0]-1000;        
        Interest::onlyTrashed()->find($interest_id)->restore();
        conceptxinterest::onlyTrashed()->where("interest_id",$interest_id)->restore();
        return Redirect::to('interests/trash/trash');
    }

    public function add_show($id)
    {
        $interest = Interest::find(Hashids::decode($id)[0]-1000);
        if(is_null($interest))
        {
            return Redirect::to('interests/');
        }

        $gc = new generalContainer;
        $gc->default_buttons = false;
        $gc->add_buttons = true;
        $gc->table = true;
        $gc->page_name = "Lista de Grupos del Interés: ".$interest->name;
        $gc->page_description = "Esta lista contiene grupos de descuentos";
        $gc->entity_to_edit = $interest;

        $gc->groups = Group::whereHas('interestxgroup', function($q) use($interest){
                                    $q->where('id_interest', $interest->id);
                                })->get();
        //echo dd($gc->groups);
        $gc->url_base="interests";
        return view('cms.discounts.list_add', compact('gc'));
    }

    public function add_elements($id)
    {
        $discount = Interest::find(Hashids::decode($id)[0]-1000);
        if(is_null($discount))
        {
            return Redirect::to('interests/');
        }

        $gc = new generalContainer;
        $gc->trash = true;
        $gc->table = true;
        $gc->select = true;
        $gc->page_name = "Interés: ".$discount->name;
        $gc->page_description = "Seleccione los grupos y luego de click en confirmar";
        $gc->entity_to_edit = $discount;
        $gc->groups_students = Group::all();
        $gc->url_base="interests";
        return view('cms.concepts.push_add', compact('gc'));
    }

    public function add_store(Request $request)
    {
        
        $config = Configuration::where('current',1)->first();
        //first the student will be added to a classroom
        $id_md5_elements = preg_split("`,`", $request->elements_id);
        
        $id_elements=array();
        for ($i=1; $i < count($id_md5_elements); $i++) {

            $id_elements[$i-1]= Hashids::decode($id_md5_elements[$i])[0]-1000;            

            $relation = interestXgroup::where('id_interest',$request->entity_to_edit_id)->
                                        where('id_group',$id_elements[$i-1])                                        
                                        ->first();
            if(is_null($relation))
            {
               $relation = new interestXgroup;
                $relation->id_interest = $request->entity_to_edit_id;
                $relation->id_group = $id_elements[$i-1];
                $relation->save(); 
            }            

        }

        return Redirect::to('interests/'.Hashids::encode($request->entity_to_edit_id+1000).'/add');
 
    }

    public function add_inactive($id_discount, $id_group)
    {
        $relation = interestXgroup::where('id_interest',Hashids::decode($id_discount)[0]-1000)->
                                        where('id_group',Hashids::decode($id_group)[0]-1000)->first();

        $relation->delete();
        return Redirect::to('interests/'.$id_discount.'/add');
    }
}
