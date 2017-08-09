<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

$gc->page_name = $oStudent->full_name;
$gc->default_buttons = false;
$gc->add_buttons = false;
$gc->table = false;
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
			<th>Total a Pagar</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$iIndexConcept=1;
		?>
		@foreach($cConcepts as $mConcept)

			@if($mConcept->amount > $mConcept->total_paid)
			<tr class="payment_concept_tr_class" id={!!$mConcept->id_md5!!}>
				<th scope="row">{!!$iIndexConcept!!}</th>
				<td class="col-md-3 col-sm-3 col-xs-3">{!!$mConcept->name!!}</td>
				<td class="col-md-2 col-sm-2 col-xs-2">{!!"S/. ".$mConcept->amount!!}</td>
				<td class="col-md-2 col-sm-2 col-xs-2">{!!"S/. ".$mConcept->total_paid!!}</td>

				<td class="col-md-2 col-sm-2 col-xs-2"><div class="col-md-12 col-sm-12 col-xs-12">
					<input autofocus type="number" min="1" id={!!"concept_".$mConcept->id_md5!!} class="form-control col-md-12 col-xs-12 amountToPayConcept">
				</div></td>
				<td id={!!"total_".$mConcept->id_md5!!}>{!!"S/. "."0"!!}</td>         
			</tr>			
			<?php 
				$iIndexDiscount=1;

			?>

			@foreach($cDiscountxStudents as $oDiscount)
				@if($oDiscount->id_concept == $mConcept->id)
					<tr class="payment_concept_tr_class" id={!!"tr_disc_".$mConcept->id_md5."_".$oDiscount->id_md5!!} style="display:none;">
						<th scope="row">Dscto</th>
						<td class="col-md-3 col-sm-3 col-xs-3">{!!$oDiscount->name!!}</td>
						<td class="col-md-2 col-sm-2 col-xs-2">{!!"S/. ".$oDiscount->amount!!}</td>
						<td class="col-md-2 col-sm-2 col-xs-2"></td>

						<td class="col-md-2 col-sm-2 col-xs-2"><div class="col-md-12 col-sm-12 col-xs-12">
							<input autofocus type="number" min="1" id={!!"discount_".$mConcept->id_md5."_".$oDiscount->id_md5!!} class="form-control col-md-12 col-xs-12 amountToPayDiscount">
						</div></td>
						<td id={!!"total_disc_".$mConcept->id_md5."_".$oDiscount->id_md5!!}>{!!"S/. "."0"!!}</td>         
					</tr>
					<?php 
						$iIndexDiscount++;
					?>
				@endif
			@endforeach

			<?php 
				$iIndexInterest=1;
			?>
			@foreach($cInterestxStudents as $oInterest)

				@if($oInterest->id_concept == $mConcept->id)
					<tr class="payment_concept_tr_class" id={!!"tr_int_".$mConcept->id_md5."_".$oInterest->id_md5!!} style="display:none;">
						<th scope="row">Interés</th>
						<td class="col-md-3 col-sm-3 col-xs-3">{!!$oInterest->name!!}</td>
						<td class="col-md-2 col-sm-2 col-xs-2">{!!"S/. ".$oInterest->amount!!}</td>
						<td class="col-md-2 col-sm-2 col-xs-2"></td>

						<td class="col-md-2 col-sm-2 col-xs-2"><div class="col-md-12 col-sm-12 col-xs-12">
							<input autofocus type="number" min="1" id={!!"interest_".$mConcept->id_md5."_".$oInterest->id_md5!!} class="form-control col-md-12 col-xs-12 amountToPayInterest">
						</div></td>
						<td id={!!"total_int_".$mConcept->id_md5."_".$oInterest->id_md5!!}>{!!"S/. "."0"!!}</td>         
					</tr>
					<?php 
						$iIndexInterest++;
					?>
				@endif
			@endforeach

			<?php $iIndexConcept++;?>
			@endif
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
		var bJumpNextInput = true;

		$(".amountToPayConcept").bind('keypress', function(e) {
			if(e.which == 13 || e.keyCode == 9) {

				event.preventDefault();

				var sId_Sender = e["currentTarget"]["attributes"][3]["nodeValue"];


				//CASE CONCEPT
				if (sId_Sender.indexOf("concept_") >= 0){
					
					var id_concept = sId_Sender.replace('concept_','');
					var sAmount = $("#concept_"+id_concept).val();

					console.log("id_concept: "+id_concept);
					
					if (sAmount.length>0 && sAmount!='0') {
						aData[id_concept] = {};
						aData[id_concept]['id_md5'] = id_concept;
						aData[id_concept]['using'] = true;
						aData[id_concept]['amount'] = parseFloat(sAmount);
						aData[id_concept]['discount'] = {};
						aData[id_concept]['interest'] = {};

						//GET ALL DISCOUNTS
						$('*[id*=discount_'+id_concept+']').each(function() {							
						    var id_discount = this.id.replace('discount_'+id_concept+'_','');
						    var sValue = this.value;
						    var dValue = 0;
						    console.log("        id_discount: "+id_discount+"       sValue: "+sValue);
						    //IF THE VALUE IS NOT EMPTY
						    if (sValue.length>0) {
								dValue = parseFloat(sValue);
								aData[id_concept]['discount'][id_discount] = {};
								aData[id_concept]['discount'][id_discount]['id_md5'] = id_discount;
								aData[id_concept]['discount'][id_discount]['amount'] = dValue;
						    }
						    //SHOWING INPUTS
						    $("#tr_disc_"+id_concept+"_"+id_discount).show();			    
						});

						//GET ALL INTERESTS
						$('*[id*=interest_'+id_concept+']').each(function() {							
						    var id_interest = this.id.replace('interest_'+id_concept+'_','');
						    var sValue = this.value;
						    var dValue = 0;
						    console.log("        id_interest: "+id_interest+"       sValue: "+sValue);
						    //IF THE VALUE IS NOT EMPTY
						    if (sValue.length>0) {
								dValue = parseFloat(sValue);
								aData[id_concept]['interest'][id_interest] = {};
								aData[id_concept]['interest'][id_interest]['id_md5'] = id_interest;
								aData[id_concept]['interest'][id_interest]['amount'] = dValue;
						    }
						    //SHOWING INPUTS
						    $("#tr_int_"+id_concept+"_"+id_interest).show();    
						});

						//UPDATING VALUE
						$("#total_"+id_concept).html('S/. '+sAmount);
					}else{
						//CASE DELETE ELEMENT
						aData[id_concept]['using'] = false;
						aData[id_concept]['amount'] = 0;

						$('*[id*=discount_'+id_concept+']').each(function() {
							var id_discount = this.id.replace('discount_'+id_concept+'_','');
							$("#tr_disc_"+id_concept+"_"+id_discount).hide();
						});

						$("#total_"+id_concept).html('S/. 0');
					}

					//UPDATING VALUE
					var dTotalDocument = 0;
		    		$.each(aData, function(key, value) {
		    			if (aData[key]['using']) {
		    				var dAmount = aData[key]['amount'];
			    			var dAmountInt = 0;
			    			var dAmountDesc = 0;
			    			var auxDiscountData = aData[key]['discount'];
			    			var auxInterestData = aData[key]['interest'];
			    			
			    			//DISCOUNT
			    			$.each(auxDiscountData, function(keyDiscount, valueDiscount) {
			    				dAmountDesc += auxDiscountData[keyDiscount]['amount'];
			    			});

			    			//INTEREST
			    			$.each(auxInterestData, function(keyInterest, valueInterest) {
			    				dAmountInt += auxInterestData[keyInterest]['amount'];
			    			});

			    			console.log(dTotalDocument);
			    			console.log(dAmount);
			    			console.log(dAmountInt);
			    			console.log(dAmountDesc);

			    			dTotalDocument += dAmount + dAmountInt - dAmountDesc;	
		    			}			    			    
		    		});
		    		console.log("dTotalDocument: "+dTotalDocument);	    			
		    		$("#document_amount").val('S/. '+dTotalDocument);

					//NEXT INPUT FOCUS
					if (bJumpNextInput) {
						var inputs = $(this).closest('form').find(':input');
			    		inputs.eq( inputs.index(this)+ 1 ).focus();
			    		bJumpNextInput = true;
					}

					console.log(aData);
				}
				
		    }
		})

		$(".amountToPayDiscount").bind('keypress', function(e) {
			if(e.which == 13 || e.keyCode == 9) {
				event.preventDefault();

				//GETTING ID OF CONCEPT
				var sId_Sender = e["currentTarget"]["attributes"][3]["nodeValue"];
				var sAmount = $("#"+sId_Sender).val();		
				var sIdConcept = sId_Sender.replace('discount_','');
				var sIdConcept_IdDiscount = sIdConcept;
				var iIndexFirstUnderLine = sIdConcept.indexOf("_")
				sIdConcept = sIdConcept.substring(0,iIndexFirstUnderLine);
				
				//JUMPING NEXT INPUT
				bJumpNextInput = false;
				var inputs = $(this).closest('form').find(':input');
	    		inputs.eq( inputs.index(this)+ 1 ).focus();	

				//SENDING EVENT
				var e = jQuery.Event("keypress");
				e.which = 13; //choose the one you want
				e.keyCode = 13;
				$("#concept_"+sIdConcept).trigger(e);
				bJumpNextInput = true;

				//UPDATING AMOUNT
				$("#total_disc_"+sIdConcept_IdDiscount).html('- S/. '+sAmount);
			}
		})

		$(".amountToPayInterest").bind('keypress', function(e) {
			if(e.which == 13 || e.keyCode == 9) {
				event.preventDefault();

				//GETTING ID OF CONCEPT
				var sId_Sender = e["currentTarget"]["attributes"][3]["nodeValue"];
				var sAmount = $("#"+sId_Sender).val();		
				var sIdConcept = sId_Sender.replace('interest_','');
				var sIdConcept_IdInterest = sIdConcept;
				var iIndexFirstUnderLine = sIdConcept.indexOf("_")
				sIdConcept = sIdConcept.substring(0,iIndexFirstUnderLine);
				
				//JUMPING NEXT INPUT
				bJumpNextInput = false;
				var inputs = $(this).closest('form').find(':input');
	    		inputs.eq( inputs.index(this)+ 1 ).focus();	

				//SENDING EVENT
				var e = jQuery.Event("keypress");
				e.which = 13; //choose the one you want
				e.keyCode = 13;
				$("#concept_"+sIdConcept).trigger(e);
				bJumpNextInput = true;

				//UPDATING INTEREST
				$("#total_int_"+sIdConcept_IdInterest).html('S/. '+sAmount);
			}
		})
			
		$( "#form" ).submit(function( event ) {
			$("#concepts").val(JSON.stringify(aData));  		  	
		});

	});
</script>
@endsection