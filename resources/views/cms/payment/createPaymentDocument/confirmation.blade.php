<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

if(is_null($gc)){
	dd("Error - General Container not found");
}
$gc->page_name = "La boleta fue creada exitósamente";
$gc->default_buttons = false;
$gc->add_buttons = false;
$gc->select = true;
$gc->date = true;
?>

@extends('cms.templates.template')

@section('content')
<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    <a href="/createPaymentDocument/" type="submit" class="btn btn-primary">Insertar Nueva Boleta</a>
  </div>
</div>
@endsection

@section('scripts')

@endsection