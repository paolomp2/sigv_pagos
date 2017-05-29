<?php
	if(is_null($gc)){
		dd("Error - General Container not found");
	}
	$gc->page_name = "Creación Masiva de Boletas (TEST)";
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;
    $gc->date = true;
?>

@extends('cms.templates.template')

@section('content')
	{!!Form::open(['route'=>'Test.createPaymentDocument.GeneratePayments_02','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}    
	
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-3">Número de Boleta*</label>
			<div class="col-md-3 col-sm-3 col-xs-3">
			  <input autofocus type="text" minlength="5" maxlength="50"  id="name" name="name" class="form-control col-md-7 col-xs-12" placeholder="Número de Boleta">
			</div>			
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monto(S/.)<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<input type="number" min="10" id="amount" name="amount" required="required" class="form-control col-md-7 col-xs-12" >
			</div>
		</div>   

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha Inicial<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-12 col-xs-12">
			  <div class="controls">                          
			      <input required="required" type="text" class="form-control has-feedback-left" id="date_start" name="date_start" placeholder="Fecha de emisión" aria-describedby="inputSuccess2Status2">
			      <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
			  </div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha Final<span class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-12 col-xs-12">
			  <div class="controls">                          
			      <input required="required" type="text" class="form-control has-feedback-left" id="date_end" name="date_end" placeholder="Fecha de emisión" aria-describedby="inputSuccess2Status2">
			      <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
			  </div>
			</div>
		</div>

		<div class="ln_solid"></div>
		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			  <button type="submit" class="btn btn-success">Crear</button>
			</div>
		</div>


	</form>
@endsection

@section('scripts')

<!-- /datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {      
    });

    $('#date_start').daterangepicker({
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
    $('#date_end').daterangepicker({
      dateFormat: 'yy-mm-dd',
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  </script>
@endsection