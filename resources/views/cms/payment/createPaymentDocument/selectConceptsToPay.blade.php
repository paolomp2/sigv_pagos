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
{!!Form::open(['route'=>'payments.showReceiptConsole','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}    

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Número de Documento :
		</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<input autofocus type="text" minlength="5" id="name" name="name" readonly="" class="form-control col-md-7 col-xs-12" value={!!$payment_document_number!!}>
		</div>
	</div>

	<div class="ln_solid"></div>

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
		      <input autofocus type="number" min="1" id={!!"concept_".$mConcept->id_md5!!} class="form-control col-md-7 col-xs-12 amountToPay">
		    </div></td>

	      <td><div class="col-md-12 col-sm-12 col-xs-12">
		      <input autofocus type="number" min="1" id={!!"discount_".$mConcept->id_md5!!} class="form-control col-md-7 col-xs-12 amountToPay">
		    </div></td>

	      <td><div class="col-md-12 col-sm-12 col-xs-12">
		      <input autofocus type="number" min="1" id={!!"interest_".$mConcept->id_md5!!} class="form-control col-md-7 col-xs-12 amountToPay">
		    </div></td>	      

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
	
	$(".amountToPay").bind('keypress', function(e) {		
	    if(e.which == 13) {
	    	event.preventDefault();

	    	var sId_md5 = e["currentTarget"]["attributes"][3]["nodeValue"].replace('concept_','');
	    	var dAmount = $("#concept_"+sId_md5).val();

	    	console.log(sId_md5);
	    	console.log(dAmount);

	    	var index = $('.inputs').index(this) + 1;
         	$('.inputs').eq(index).focus();

	    }
	})
	

	document.getElementById("form").addEventListener("submit", prepareRequest);
	function prepareRequest(){		
		document.getElementById('request').innerHTML = '<input name="amountToPay" type="hidden" value ='+document.getElementById('amountToPay').value+'>';
	}

});
</script>
@endsection