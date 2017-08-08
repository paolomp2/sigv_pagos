<?php
	use sigc\Http\Containers\generalContainer;

	$gc = new generalContainer;
    $gc->page_name = "Inserte el número de boleta";
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;    
?>

@extends('cms.templates.template')

@section('content')
{!!Form::open(['route'=>'payments.void.deleteDocument','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}    

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Número de Documento :
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type="number" minlength="5" id="document_number" name="document_number" class="form-control col-md-7 col-xs-12" >
	</div>
</div>

<div class="ln_solid"></div>
<div class="form-group">
	<div id="request">
	</div>
	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		<button type="submit" class="btn btn-success">Continuar</button>
	</div>
</div>

</form>
@endsection

@section('scripts')
@endsection