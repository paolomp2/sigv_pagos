<?php
	if(is_null($gc)){
		dd("Error - General Container not found");
	}
    $gc->url_base = "payment";
    $gc->page_name = "Seleccione a un alumno";
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;
    $gc->breadcrumb('show_students');
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
		        		{!!$student->full_name!!}
					</option>
		        @endforeach
		      </select>
		    </div>
		</div>

		<div class="ln_solid"></div>
		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			  <a id="students" href="/getDebsList/{!!$gc->students[0]->id_md5!!}" class="btn btn-success">Continuar</a>
			</div>
		</div>

	</div>
@endsection

@section('scripts')
<script >
	$(document).ready(function() {
      $(".select2_single").select2();  
    });
    function ChangeID() {
      //capturar select
      var e = document.getElementById("student");
      //capturar monto del grupo
      var id = e.options[e.selectedIndex].value;      
      document.getElementById("students").href="/getDebsList/"+id; 
    }
</script>
@endsection