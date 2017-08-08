<?php
	if(is_null($gc)){
		dd("Error - General Container not found");
	}
    $gc->page_name = "Lista de deudas del alumno: ".$gc->entity_to_edit->full_name;
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;
    $gc->breadcrumb('show_students.getDebtsList.'.$gc->entity_to_edit->id_md5);
?>

@extends('cms.templates.template')

@section('content')
	<div class="form-horizontal form-label-left">
		<div class="form-group">
		    <label class="control-label col-md-10 col-sm-6 col-xs-12" for="first-name">Monto a pagar: <span class="required">*</span>
		    </label>
		    <div class="col-md-2 col-sm-6 col-xs-12">
		      <input autofocus type="number" min="1" id="amountToPay" name="amountToPay" required="required" class="form-control col-md-7 col-xs-12">
		    </div>
		</div>
	</div>
	<table id="list_table" class="display dataTable">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Concepto</th>
	      <th>Monto</th>
	      <th>Dscts</th>
  	      <th>Interés</th>
	      <th>Pagado</th>
	      <th>Fecha Vencimiento</th>
	      <th>Monto a Pagar</th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php 
	    	$i=1;
	    	$indexDiscount = 0;
	    	$indexConcept = 0;
    	?>
	    @foreach($gc->concepts as $mConcept)
	    <?php 	    	
	    	$indexDiscount = $i;
	    	$indexConcept++;
    	?>
	    <tr class="payment_concept_tr_class" id={!!$mConcept->id_md5!!}>
	      <th scope="row">{!!$indexConcept!!}</th>
	      <td>{!!$mConcept->name!!}</td>
	      <td>{!!"S/. ".$mConcept->amount!!}</td>
	      <td><a id="{!!"a_discount_".$indexConcept!!}" href="#" class="toggler" data-prod-cat="{!!($indexConcept)!!}">{!!"S/. #"!!}</a></td>
	      <td><a id="{!!"a_interest_".$indexConcept!!}" href="#" class="toggler" data-prod-int="{!!($indexConcept)!!}">{!!"S/. #"!!}</a></td>
	      <td>{!!"S/. ".$mConcept->total_paid!!}</td>
	      <td>{!!$mConcept->fecha_vencimiento!!}</td>
	      <td>{!!"S/. "."0"!!}</td>         
	    </tr>
	    	
	    	<?php $iLastindexDiscount=-1;?>
	    	@foreach($cDiscountxStudents as $mDiscountxStudents)
	    		@if($mConcept->id == $mDiscountxStudents->id_concept)
	    			@if($mDiscountxStudents->id_discount != $iLastindexDiscount)
				    	<tr class="payment_discount_tr_class  {!!"cat".($indexConcept)!!}" style="display:none;">
				    		<th></th>
				    		<td>{!!$mDiscountxStudents->name!!}</td>
				    		<td></td>
				    		<td>{!!"S/. ".$mDiscountxStudents->amount!!}</td>
				    		<td>S/. 0</td>
				    		<td></td>
				    		<td>{!!$mDiscountxStudents->expiration_date!!}</td>
				    	</tr>
				    	<?php $iLastindexDiscount=$mDiscountxStudents->id_discount;?>
				    	<?php $i++;?>
			    	@endif
	    		@endif	    		
	    	@endforeach

	    	@foreach($cInterestxStudents as $oInterestxStudents)
				@if($mConcept->id == $oInterestxStudents->id_concept)
					<tr class="payment_interest_tr_class  {!!"cat".($indexConcept)!!}" style="display:none;">
						<th></th>
				    		<td>{!!$oInterestxStudents->name!!}</td>
				    		<td></td>
				    		<td>S/. 0</td>
				    		<td>{!!"S/. ".$oInterestxStudents->amount!!}</td>
				    		<td></td>
				    		<td>{!!"0"!!}</td>
					</tr>
				@endif	
			@endforeach

	    	<?php $i++;?>
	    @endforeach
	                  
	  </tbody>
	</table>

	{!!Form::open(['route'=>'payments.showReceiptConsole','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}             
		<div class="ln_solid"></div>
		<div class="form-group">
			<div id="request">
			</div>
			<input name="id_student" type="hidden" value = "{!!$gc->entity_to_edit->id!!}">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			  <button type="submit" class="btn btn-success">Continuar</button>
			</div>
		</div>

	</form>
@endsection

@section('scripts')
<script >
$(document).ready(function() { 
	
	//calculate the total discount amount
	var table = document.getElementById("list_table");
	var iRowConceptToEdit = 0;
	var iDiscountAcumulated = 0;
	var iInterestAcumulated = 0;
	var bDiscountReade = false;
	var iCountTrOnTable = document.getElementById("list_table").getElementsByTagName("tr").length;
	//row[0] is the header
	for (var i = 1, row; row = table.rows[i]; i++) {
		var classname = table.rows[i].className;
		if(classname.split(/[ ,]+/)[0]=="payment_concept_tr_class"){			
			if(iRowConceptToEdit>0){
				document.getElementById("a_discount_"+iRowConceptToEdit).innerHTML = document.getElementById("a_discount_"+iRowConceptToEdit).innerHTML.replace("#",iDiscountAcumulated);
				document.getElementById("a_interest_"+iRowConceptToEdit).innerHTML = document.getElementById("a_interest_"+iRowConceptToEdit).innerHTML.replace("#",iInterestAcumulated);
			}
			iRowConceptToEdit++;
			iDiscountAcumulated = 0;
			iInterestAcumulated = 0;
			//CASE: THE CONCETP IS THE LAST ROW IN THE TABLE, THEN THE TOTAL DISCOUNT VALUE SHOULD BE REPLACED BY ZERO
			if(i == (iCountTrOnTable-1)){
				document.getElementById("a_discount_"+iRowConceptToEdit).innerHTML = document.getElementById("a_discount_"+iRowConceptToEdit).innerHTML.replace("#",iDiscountAcumulated);
				document.getElementById("a_interest_"+iRowConceptToEdit).innerHTML = document.getElementById("a_interest_"+iRowConceptToEdit).innerHTML.replace("#",iInterestAcumulated);
			}
		}else{
			iDiscountAcumulated += parseInt(table.rows[i].cells[3].innerHTML.split(/[ ,]+/)[1]);
			iInterestAcumulated += parseInt(table.rows[i].cells[4].innerHTML.split(/[ ,]+/)[1]);

			if(iRowConceptToEdit>0 && i == (iCountTrOnTable-1)){
				document.getElementById("a_discount_"+iRowConceptToEdit).innerHTML = document.getElementById("a_discount_"+iRowConceptToEdit).innerHTML.replace("#",iDiscountAcumulated);
				document.getElementById("a_interest_"+iRowConceptToEdit).innerHTML = document.getElementById("a_interest_"+iRowConceptToEdit).innerHTML.replace("#",iInterestAcumulated);
			}

		}
	}
	//
	$(".toggler").click(function(e){
        e.preventDefault();
        $('.cat'+$(this).attr('data-prod-cat')).toggle();
    });

    $("#amountToPay").bind('keypress', function(e) {
	    if(e.which == 13) {
	    	var iAmoutPayable = parseInt(document.getElementById("amountToPay").value);	    	
	    	var iIndexConcept = 1;
	        for (var i = 1, row; row = table.rows[i]; i++) {
	        	var classname = table.rows[i].className;
	        	if(classname.split(/[ ,]+/)[0]=="payment_concept_tr_class"){
	        		console.log(iIndexConcept);
	        		var iAmountConcept = parseInt(table.rows[i].cells[2].innerHTML.split(/[ ,]+/)[1]);
	        		var iAmountDiscount = parseInt((document.getElementById("a_discount_"+iIndexConcept).innerHTML.split(/[ ,]+/))[1]);
	        		var iAmountInterest = parseInt((document.getElementById("a_interest_"+iIndexConcept).innerHTML.split(/[ ,]+/))[1]);
	        		var iAmountPaid = parseInt(table.rows[i].cells[5].innerHTML.split(/[ ,]+/)[1]);
	        		var iRemainingAmount = iAmountConcept - iAmountDiscount - iAmountPaid + iAmountInterest;
	        		if((iRemainingAmount)>=iAmoutPayable){
	        			table.rows[i].cells[7].innerHTML = "S/. "+iAmoutPayable;
	        			iAmoutPayable = 0;
	        			iRemainingAmount = 0;
	        			
	        		}else{
	        			table.rows[i].cells[7].innerHTML = "S/. "+iRemainingAmount;
	        			iAmoutPayable = iAmoutPayable - iRemainingAmount;
	        		}
	        		iIndexConcept = iIndexConcept + 1;
	        	}
	        }
	    }
	});

	document.getElementById("form").addEventListener("submit", prepareRequest);

	function prepareRequest(){		
		document.getElementById('request').innerHTML = '<input name="amountToPay" type="hidden" value ='+document.getElementById('amountToPay').value+'>';
	}

});
</script>
@endsection