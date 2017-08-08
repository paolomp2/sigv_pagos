<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

$gc->page_name = $oStudent->full_name;
$gc->default_buttons = false;
$gc->add_buttons = false;
$gc->select = true;
$gc->breadcrumb('show_students.getDebtsList.');

?>

@extends('cms.templates.template')

@section('content')
{!!Form::open(['route'=>'payments.saveDocumentPayment','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}    

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Número de Documento :
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type="text" minlength="5" id="document_number" name="document_number" readonly="" class="form-control col-md-7 col-xs-12" value={!!$payment_document_number!!}>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Fecha de Pago :
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type="text" minlength="5" id="document_date" name="document_date" readonly="" class="form-control col-md-7 col-xs-12" value={!!$creation_date!!}>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Monto Total del Documento :
	</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type="text" minlength="5" id="document_amount" name="document_amount" readonly="" class="form-control col-md-7 col-xs-12" value="S/. 0">
	</div>
</div>

<input type="hidden" type="text" minlength="5" id="concepts" name="concepts" readonly="" class="form-control col-md-7 col-xs-12" value="">



<div class="ln_solid"></div>

<table id="list_table" class="display dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th>Concepto</th>
			<th>Monto Original</th>
			<th>Pagado</th>
			<th>A Pagar</th>	      
			<th colspan="2">Dscts</th>
			<th colspan="2">Interés</th>	      
			<th>Total a Pagar</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$i=1;
		$indexDiscount = 0;
		$indexConcept = 0;
		?>
		@foreach($cConcepts as $mConcept)
		<?php 	    	
		$indexDiscount = $i;
		$indexConcept++;
		?>
		<tr class="payment_concept_tr_class" id={!!$mConcept->id_md5!!}>
			<th scope="row">{!!$indexConcept!!}</th>
			<td class="col-md-2 col-sm-2 col-xs-2">{!!$mConcept->name!!}</td>
			<td class="col-md-1 col-sm-1 col-xs-1">{!!"S/. ".$mConcept->amount!!}</td>
			<td class="col-md-1 col-sm-1 col-xs-1">{!!"S/. ".$mConcept->total_paid!!}</td>

			<td class="col-md-1 col-sm-1 col-xs-1"><div class="col-md-12 col-sm-12 col-xs-12">
				<input autofocus type="number" min="1" id={!!"concept_".$mConcept->id_md5!!} class="form-control col-md-12 col-xs-12 amountToPay">
			</div></td>

			<td><div class="col-md-12 col-sm-12 col-xs-12">
				<select id={!!"discount_id_".$mConcept->id_md5!!} name={!!"discount_id_".$mConcept->id_md5!!} class="select2_single form-control ">
					<?php $AtLeastOneDiscountSelected = false;?>
					@foreach($cDiscountxStudents as $oDiscount)						
						@if($oDiscount->id_concept == $mConcept->id)
							<?php $AtLeastOneDiscountSelected = true;?>
							<option id="{!!$oDiscount->id_md5!!}" value="{!!$oDiscount->id_md5!!}">{!!$oDiscount->name!!}</option>
						@endif
					@endforeach
					@if($AtLeastOneDiscountSelected == false)
						<option id="DiscountNull" value="DiscountNull">Otro</option>
					@endif
				</select>
			</div></td>

			<td class="col-md-1 col-sm-1 col-xs-1"><div class="col-md-12 col-sm-12 col-xs-12">
				<input autofocus type="number" min="1" id={!!"discount_".$mConcept->id_md5!!} class="form-control col-md-6 col-xs-6 amountToPay">
			</div></td>
			
			<td><div class="col-md-12 col-sm-12 col-xs-12">
				<select id={!!"interest_id_".$mConcept->id_md5!!} name={!!"interest_id_".$mConcept->id_md5!!} class="select2_single form-control ">
					<?php $AtLeastOneInterestSelected = false;?>
					@foreach($cInterestxStudents as $oInterest)						
						@if($oInterest->id_concept == $mConcept->id)
							<?php $AtLeastOneInterestSelected = true;?>
							<option id="{!!$oInterest->id_md5!!}" value="{!!$oInterest->id_md5!!}">{!!$oInterest->name!!}</option>
						@endif
					@endforeach
					@if($AtLeastOneInterestSelected == false)
						<option id="InterestNull" value="InterestNull">Otro</option>
					@endif
				</select>
			</div></td>

			<td class="col-md-1 col-sm-1 col-xs-1">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<input autofocus type="number" min="1" id={!!"interest_".$mConcept->id_md5!!} class="form-control col-md-12 col-xs-12 amountToPay">
				</div>
			</td>


			<td id={!!"total_".$mConcept->id_md5!!}>{!!"S/. "."0"!!}</td>         
		</tr>

		<?php $i++;?>
		@endforeach

	</tbody>
</table>


<div class="ln_solid"></div>
<div class="form-group">
	<div id="request">
	</div>
	<input name="id_student" type="hidden" value = "{!!$oStudent->id!!}">
	<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		<button type="submit" class="btn btn-success">Continuar</button>
	</div>
</div>

</form>
@endsection

@section('scripts')
<script >
	$(document).ready(function() { 

		var aData = {};

		$(".amountToPay").bind('keypress', function(e) {		

			if(e.which == 13 || e.keyCode == 9) {

				event.preventDefault();

				var sId_md5 = e["currentTarget"]["attributes"][3]["nodeValue"].replace('concept_','');
				sId_md5 = sId_md5.replace('discount_','');
				sId_md5 = sId_md5.replace('interest_','');

				var sAmount = $("#concept_"+sId_md5).val();
				var sAmountInt = $("#interest_"+sId_md5).val();
				var sAmountDesc = $("#discount_"+sId_md5).val();

				var dAmount = 0;
				var dAmountInt = 0;
				var dAmountDesc = 0;

				var dTotalRow = 0;

				if(sAmount!=""){
					dAmount = parseFloat(sAmount);
				}
				if(sAmountInt!=""){
					dAmountInt = parseFloat(sAmountInt);
				}
				if(sAmountDesc!=""){
					dAmountDesc = parseFloat(sAmountDesc);
				}


				dTotalRow = dAmount + dAmountInt - dAmountDesc;

				$("#total_"+sId_md5).html('S/. '+dTotalRow)

		    	//SAVING DATA ON ARRAY
		    	if(dAmount>0){
		    		aData[sId_md5] = {};
		    		aData[sId_md5]['id_md5'] = sId_md5;
		    		aData[sId_md5]['concept'] = dAmount;
		    		aData[sId_md5]['interest'] = dAmountInt;
		    		aData[sId_md5]['discount'] = dAmountDesc;
		    		aData[sId_md5]['discount_id'] = $("#discount_id_"+sId_md5).val();
		    		aData[sId_md5]['interest_id'] = $("#interest_id_"+sId_md5).val();

		    		var dTotalDocument = 0;
		    		$.each(aData, function(key, value) {
		    			var dAmount = aData[key]['concept'];
		    			var dAmountInt = aData[key]['interest'];
		    			var dAmountDesc = aData[key]['discount'];
		    			dTotalDocument = dTotalDocument + dAmount + dAmountInt - dAmountDesc;	    
		    		});
		    		$("#document_amount").val('S/. '+dTotalDocument);
		    	}

		    	console.log(aData);

		    	//NEXT INPUT FOCUS
		    	var inputs = $(this).closest('form').find(':input');
		    	inputs.eq( inputs.index(this)+ 1 ).focus();
		    }
		})

		$( "#form" ).submit(function( event ) {
			$("#concepts").val(JSON.stringify(aData));  		  	
		});

	});

	$(document).ready(function() {
		$(".select2_single").select2();  
	});
</script>
@endsection