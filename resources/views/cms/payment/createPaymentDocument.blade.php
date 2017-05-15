<?php
	if(is_null($gc)){
		dd("Error - General Container not found");
	}
	$gc->page_name = "Creación manual de boleta";
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;
?>

@extends('cms.templates.template')

@section('content')
	<div class="form-horizontal form-label-left">           
		
		<div class="form-group">
		    <label class="control-label col-md-3 col-sm-3 col-xs-12">seleccione alumno*</label>
		    <div class="col-md-6 col-sm-12 col-xs-12">
		      <select onchange="ChangeID()"  id="student" name="student" class="select2_single form-control">
		        @foreach($gc->students as $student)
		        	<option value="{!!$student->id_md5!!}">
		        		{!!$student->last_name!!}{!!" "!!}{!!$student->maiden_name!!}{!!","!!}
		        		{!!$student->first_name!!}{!!" "!!}{!!$student->middle_name!!}
					</option>
		        @endforeach
		      </select>
		    </div>
		</div>

		<div class="ln_solid"></div>
		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			  <button type="submit" class="btn btn-success">Guardar</button>
			</div>
		</div>

	</div>
@endsection

@section('scripts')
@endsection