<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;
use sigc\Http\Containers\concept_groupsContainer;

use sigc\Concept_group;
use sigc\Concept;
use sigc\conceptxdiscount;
use sigc\Discount;
use sigc\Interest;
use sigc\Configuration;

use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;

class concept_groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $concept_groups = Concept_group::orderBy("year","desc")->orderBy("name","desc")->get();        
        $gc = new generalContainer;
        $gc->url_base = "concepts_groups";
        $c_gc = new concept_groupsContainer;
        $c_gc->concept_groups = $concept_groups;
        
        foreach ($c_gc->concept_groups as $concept_group) {
            if($concept_group->id_md5==""){
                $concept_group->id_md5 = Hashids::encode($concept_group->id+1000);
                $concept_group->save();
            }
        }

        //echo dd($gc->questions);
        $gc->table = true;
        $gc->page_name = "Lista de Grupos de Conceptos";
        $gc->page_description = "Esta lista contiene grupos de conceptos";
        $gc->breadcrumb('concepts_groups');

        return view('cms.concept_groups.list', compact('gc','c_gc'));
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
        $gc->page_name = "Crear nuevo Concepto de Grupo";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->select = true;
        $gc->url_base = "concepts_groups";
        $gc->form = true;
        $gc->breadcrumb('concepts_groups.create');
        $c_gc = new concept_groupsContainer;
        $gc->discounts = Discount::all();
        $gc->interests = Interest::all();
        $gc->configurations = Configuration::all();
        return view('cms.concept_groups.form', compact('gc','c_gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $mConceptGroup = new Concept_Group;
        $mConceptGroup->name = $request->name;
        $mConceptGroup->amount = $request->amount;
        $mConceptGroup->year = $request->selected_year;
        $mConceptGroup->save();
        $mConceptGroup->set_discounts_id($request->discounts);
        $mConceptGroup->set_interests_id($request->interest);

        $cConcept = Concept::where('id_concept_group', $mConceptGroup->id)->get();

        foreach ($cConcept as $mConcept) {
            foreach ($request->discounts as $id_discount) {
                $oConceptxdiscount = new conceptxdiscount;
                $oConceptxdiscount->id_concept = $mConcept->id;
                $oConceptxdiscount->id_discount = $id_discount;
                $oConceptxdiscount->save();
            }
        }

        return Redirect::to('/concepts_groups');
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
        $cg = Concept_group::find(Hashids::decode($id)[0]-1000);
        $c_gc = new concept_groupsContainer;
        $c_gc->id = $cg->id;
        $c_gc->name = $cg->name;
        $c_gc->amount = $cg->amount; 

        $gc = new generalContainer;
        $gc->url_base = "concepts_groups";
        $gc->form = true;
        $gc->page_name = "Editar Grupo de conceptos";
        $gc->description = "Modifique los datos necesarios";
        $gc->select = true;
        $gc->discounts = Discount::all();
        $gc->interests = Interest::all();
        $gc->configurations = Configuration::all();
        $gc->discounts_id = $cg->get_discounts_id()->get();
        $gc->interests_id = $cg->get_interests_id()->get();
        $gc->breadcrumb('concepts_groups.edit.'.$cg->name);
        $gc->entity_to_edit = $cg;

        return view('cms.concept_groups.form', compact('gc','c_gc'));
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
        
        $g_c = Concept_group::find($id);
        $g_c->name =    $request->name;
        $g_c->amount =    $request->amount;
        $g_c->year = $request->selected_year;
        $g_c->save();
        $g_c->set_discounts_id($request->discounts);
        $g_c->set_interests_id($request->interest);

        return Redirect::to('/concepts_groups');
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
        $c_g = Concept_group::find(Hashids::decode($id)[0]-1000);
        $c_g->delete();

        return Redirect::to('/concepts_groups');
    }

    public function trash()
    {
        $concept_groups = Concept_group::onlyTrashed()->get();        
        
        $c_gc = new concept_groupsContainer;
        $c_gc->concept_groups = $concept_groups;
        

        foreach ($c_gc->concept_groups as $concept_group) {
            if($concept_group->id_md5==""){
                $concept_group->id_md5 = Hashids::encode($concept_group->id+1000);
                $concept_group->save();
            }
        }

        $gc = new generalContainer;
        $gc->url_base = "concepts_groups";
        $gc->trash = true;
        $gc->table = true;
        $gc->page_name = "Lista de Grupos de Conceptos";
        $gc->page_description = "Esta lista contiene grupos de conceptos";
        $gc->breadcrumb('concepts_groups.trash');
        return view('cms.concept_groups.list', compact('gc','c_gc'));
    }

    public function untrashed($id)
    {
        Concept_group::onlyTrashed()->find(Hashids::decode($id)[0]-1000)->restore();
        return Redirect::to('concepts_groups/trash/trash');
    }
}
