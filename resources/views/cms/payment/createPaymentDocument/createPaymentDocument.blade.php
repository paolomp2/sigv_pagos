<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

if(is_null($gc)){
	dd("Error - General Container not found");
}
$gc->page_name = "Creación manual de boleta";
$gc->default_buttons = false;
$gc->add_buttons = false;
$gc->select = true;
$gc->date = true;
?>

@extends('cms.templates.template')

@section('content')

{!!Form::open(['route'=>'payments.getDebsListWithOutDateLimit','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-3">Número de Boleta*</label>
		<div class="col-md-3 col-sm-3 col-xs-3">
			<input required="required" autofocus type="number" minlength="5" maxlength="50"  id="payment_document_number" name="payment_document_number" class="form-control col-md-7 col-xs-12" placeholder="Número de Boleta">
		</div>			
	</div>

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de Creación de la boleta<span class="required">*</span>
		</label>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="controls">                          
				<input required="required" type="text" class="form-control has-feedback-left" id="creation_date" name="creation_date" placeholder="Fecha de emisión" aria-describedby="inputSuccess2Status2" value="">
				<span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12">seleccione alumno*</label>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<select required="required"  id="student" name="student" class="select2_single form-control">
				@foreach($cStudents as $student)
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
			<button type="submit" class="btn btn-success">Continuar</button>
		</div>
	</div>

</form>
@endsection

@section('scripts')
<script >
	$(document).ready(function() {
		$(".select2_single").select2();  
	});
	
	$('#creation_date').daterangepicker({
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
</script>
@endsection