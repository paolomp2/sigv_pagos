<?php
	use sigc\Http\Containers\generalContainer;

	$gc = new generalContainer;

    $gc->page_name = "Page Name";
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;
    $gc->breadcrumb('show_students.getDebtsList.');

?>

@extends('cms.templates.template')

@section('content')	
	<table id="list_table" class="display dataTable">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Concepto</th>
	      <th>Monto Original</th>
	      <th>Pagado</th>
	      <th>A Pagar</th>	      
	      <th>Dscts</th>
  	      <th>Interés</th>	      
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
	      <td>{!!$mConcept->name!!}</td>
	      <td>{!!"S/. ".$mConcept->amount!!}</td>
	      <td>{!!"S/. ".$mConcept->total_paid!!}</td>

	      <td><div class="col-md-12 col-sm-12 col-xs-12">
		      <input autofocus type="number" min="1" id={!!"concept_".$mConcept->id_md5!!} name="amountToPay" required="required" class="form-control col-md-7 col-xs-12">
		    </div></td>

	      <td><div class="col-md-12 col-sm-12 col-xs-12">
		      <input autofocus type="number" min="1" id={!!"discount_".$mConcept->id_md5!!} name="amountToPay" required="required" class="form-control col-md-7 col-xs-12">
		    </div></td>

	      <td><div class="col-md-12 col-sm-12 col-xs-12">
		      <input autofocus type="number" min="1" id={!!"interest_".$mConcept->id_md5!!} name="amountToPay" required="required" class="form-control col-md-7 col-xs-12">
		    </div></td>	      

	      <td id={!!"total_".$mConcept->id_md5!!}>{!!"S/. "."0"!!}</td>         
	    </tr>

	    	<?php $i++;?>
	    @endforeach
	                  
	  </tbody>
	</table>

	{!!Form::open(['route'=>'payments.showReceiptConsole','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}             
		<div class="ln_solid"></div>
		<div class="form-group">
			<div id="request">
			</div>
			<input name="id_student" type="hidden" value = "{!!$id_student!!}">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			  <button type="submit" class="btn btn-success">Continuar</button>
			</div>
		</div>

	</form>
@endsection

@section('scripts')
<script >
$(document).ready(function() { 
	
	$("#amountToPay").bind('keypress', function(e) {
	    if(e.which == 13) {
	    	
	    }
	}

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

	document.getElementById("form").addEventListener("submit", prepareRequest);
	function prepareRequest(){		
		document.getElementById('request').innerHTML = '<input name="amountToPay" type="hidden" value ='+document.getElementById('amountToPay').value+'>';
	}

});
</script>
@endsection