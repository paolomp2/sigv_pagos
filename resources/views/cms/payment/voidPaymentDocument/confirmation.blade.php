<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

$gc->page_name = $sConfirmationMsg;
$gc->default_buttons = false;
$gc->add_buttons = false;

?>

@extends('cms.templates.template')

@section('content')
<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    <a href="/Payments/void/selectDocument/" type="submit" class="btn btn-primary">Eliminar otra boleta</a>
  </div>
</div>
@endsection

@section('scripts')

@endsection