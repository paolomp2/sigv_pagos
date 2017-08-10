<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;
use sigc\Http\Containers\generalContainer;
use sigc\Http\Containers\discountContainer;

use sigc\Concept_group;
use sigc\Concept;
use sigc\Discount;
use sigc\Group;
use sigc\Configuration;
use sigc\discountXgroup;
use sigc\conceptxdiscount;
use Vinkla\Hashids\Facades\Hashids;
use Auth;

use Redirect;

use Carbon\Carbon;

use DB;
class discountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::all();

        foreach ($discounts as $discount) {
            if($discount->id_md5==""){
                $discount->id_md5 = Hashids::encode($discount->id+1000);
                $discount->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->url_base = "discounts";
        $gc->discounts = $discounts;
        $gc->page_name = "Lista de Descuentos";
        $gc->page_description = "Esta lista contiene los descuentos aplicados a los conceptos de pago";
        $gc->breadcrumb('discounts');

        return view('cms.discounts.list', compact('gc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = Configuration::where("current",1)->first();
        $gc = new generalContainer;
        $gc->create = true;
        $gc->select = true;
        $gc->form = true;
        $gc->page_name = "Crear nuevo Descuento";
        $gc->page_description = "Inserte los campos requeridos";
        $gc->entity_to_edit = new Discount;
        $gc->entity_to_edit->days_after_expiration_date=0;
        $gc->concepts_groups = Concept_group::where("year",$config->year)->get();
        $gc->discounts = Discount::all();
        $gc->breadcrumb('discounts.create');
        $gc->configurations = Configuration::all();
        return view('cms.discounts.form', compact('gc'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $oConceptGroupSelected = Concept_group::find($request->select_concept_group);
        $discount = new Discount;
        $discount->name = $request->name;
        $discount->amount = $request->amount;
        $discount->percentage_flag = $request->radio_button_porcentage; 
        $discount->id_concept_group = $request->select_concept_group;
        $discount->save();

        if($request->radio_button_flag_before_after==0)
            $request->days_bef_aft=$request->days_bef_aft*-1;

        $discount->days_after_expiration_date = $request->days_bef_aft;
        $discount->save();

        $discount->year = $oConceptGroupSelected->year;
        $discount->id_md5 = Hashids::encode($discount->id+1000);
        $discount->save();

        return Redirect::to('/discounts');
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
        $discount = Discount::find(Hashids::decode($id)[0]-1000);

        $gc = new generalContainer;
        $gc->page_name = "Editar Descuento";
        $gc->description = "Modifique los datos necesarios";
        $gc->select=true;
        $gc->entity_to_edit=$discount;
        $gc->form = true;
        $gc->breadcrumb('discounts.edit.'.$discount->name);

        return view('cms.discounts.form', compact('gc'));
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
        $discount = Discount::find($id);
        $discount->name = $request->name;
        $discount->amount = $request->amount;
        $discount->percentage_flag = $request->radio_button_porcentage;
        if($request->radio_button_flag_before_after==0)
        {
            $request->days_bef_aft=$request->days_bef_aft*-1;
        }

        $discount->days_after_expiration_date = $request->days_bef_aft;
        $discount->save();    

        return Redirect::to('/discounts');
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
        $discount = Discount::find(Hashids::decode($id)[0]-1000);
        $discount->delete();

        return Redirect::to('/discounts');
    }

    public function trash()
    {
        $discounts = Discount::onlyTrashed()->get();

        foreach ($discounts as $discount) {
            if($discount->id_md5==""){
                $discount->id_md5 = Hashids::encode($discount->id+1000);
                $discount->save();
            }
        }

        $gc = new generalContainer;
        $gc->table = true;
        $gc->trash = true;
        $gc->url_base = "discounts";
        $gc->discounts = $discounts;
        $gc->page_name = "Lista de Descuentos eliminados";
        $gc->page_description = "Esta lista contiene los descuentos eliminados";
        $gc->breadcrumb('discounts.trash');
        return view('cms.discounts.list', compact('gc'));
    }

    public function untrashed($id)
    {
        $discount_id = Hashids::decode($id)[0]-1000;

        Discount::onlyTrashed()->find($discount_id)->restore();
        conceptxdiscount::onlyTrashed()->where("discount_id",$discount_id)->restore();

        return Redirect::to('discounts/trash/trash');
    }

    public function list_groups($id)
    {

        $discount = Discount::find(Hashids::decode($id)[0]-1000);
        if(is_null($discount))
        {
            return Redirect::to('discounts/');
        }

        $gc = new generalContainer;
        $gc->default_buttons = false;
        $gc->add_buttons = true;
        $gc->table = true;
        $gc->page_name = "Lista de Grupos del Descuento: ".$discount->name;
        $gc->page_description = "Esta lista contiene grupos de descuentos";
        $gc->entity_to_edit = $discount;

        $gc->groups = Group::whereHas('discountXgroup', function($q) use($discount){
                                    $q->where('id_discount', $discount->id);
                                })->orderBy("year","Desc")->get();
        
        $gc->url_base="discounts";

        return view('cms.discounts.list_add', compact('gc'));
    }

    public function add_elements($id)
    {
        $config = Configuration::where('current',1)->first();

        $discount = Discount::find(Hashids::decode($id)[0]-1000);
        if(is_null($discount))
        {
            return Redirect::to('discounts/');
        }

        $gc = new generalContainer;
        $gc->trash = true;
        $gc->table = true;
        $gc->select = true;
        $gc->page_name = "Descuento: ".$discount->name;
        $gc->page_description = "Seleccione los grupos y luego de click en confirmar";
        $gc->entity_to_edit = $discount;
        
        $sQuery = " select g.* 
                    from groups g, conceptxgroup cxg
                    where
                        g.id = cxg.id_group and
                        cxg.id_concept in (
                            select c.id
                            from concepts c
                            where
                                c.id_concept_group = $discount->id_concept_group
                        )
                    group by id
                    ;";
        $gc->groups_students = DB::select(DB::raw($sQuery));
        
        $gc->url_base="discounts";
        return view('cms.concepts.push_add', compact('gc'));
    }

    public function add_store(Request $request)
    {
        
        $config = Configuration::where('current',1)->first();
        //first the student will be added to a classroom
        $id_md5_elements = preg_split("`,`", $request->elements_id);
        
        $id_elements=array();
        //Apply discount to groups of students
        $schedule = new scheduleController;
        for ($i=1; $i < count($id_md5_elements); $i++) {

            $id_elements[$i-1]= Hashids::decode($id_md5_elements[$i])[0]-1000;            

            $relation = discountXgroup::where('id_discount',$request->entity_to_edit_id)->
                                        where('id_group',$id_elements[$i-1])                                        
                                        ->first();
            if(is_null($relation))
            {
               $relation = new discountXgroup;
                $relation->id_discount = $request->entity_to_edit_id;
                $relation->id_group = $id_elements[$i-1];
                $relation->save(); 
            }
        }

        return Redirect::to('discounts/'.Hashids::encode($request->entity_to_edit_id+1000).'/add');
 
    }

    public function add_inactive($id_discount, $id_group)
    {
        $id_discount_dec = Hashids::decode($id_discount)[0]-1000;
        $id_group_dec = Hashids::decode($id_group)[0]-1000;

        //Remove the relation between the discount and the group
        $relation = discountXgroup::where('id_discount',$id_discount_dec)->
                                        where('id_group',$id_group_dec)->first();
        $relation->delete();        
        return Redirect::to('discounts/'.$id_discount.'/add');
    }
}
