<?php
	if(is_null($gc)){
		dd("Error - General Container not found");
	}
    $gc->page_name = "Boleta de Pago del alumno: ".$gc->entity_to_edit->first_name.", ".$gc->entity_to_edit->last_name;
    $gc->default_buttons = false;
    $gc->add_buttons = false;
    $gc->select = true;
    $gc->breadcrumb('show_students.getDebtsList.'.$gc->entity_to_edit->id_md5);
?>

@extends('cms.templates.template')
@section('content')

	{!!Form::open(['route'=>'payments.makePayment','method'=>'POST', 'class'=>'form-horizontal form-label-left', 'id'=>'form'])!!}    
		

		<div class="form-horizontal form-label-left">
			<div class="form-group">
			    <label class="control-label col-md-3 col-sm-6 col-xs-12" for="first-name">Serie de Boucher: <span class="required">*</span>
			    </label>
			    <div class="col-md-3 col-sm-6 col-xs-12">
			      <input autofocus type="number" min="1" id="amountToPay" name="amountToPay" required="required" class="form-control col-md-7 col-xs-12">
			    </div>
			</div>
		</div>

		<div class="ln_solid"></div>
		<div class="title_center">
			<h2> Detalle de pagos a realizar</h2>
		</div>			
		<div class="ln_solid"></div>

		<table id="list_table" class="display dataTable">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>Concepto</th>
		      <th>Monto a Pagar</th>
		    </tr>
		  </thead>
		  <tbody>
		    <?php 
		    	$i=1;
		    	$iLastindexDiscount = -1;
		    	$iamountRemaining = $iAmountToPay;
		    	$amountToPayRow = 0;
		    	$iTotalDiscount = 0;
		    	$bApplyDiscounts = false;
	    	?>
		    @foreach($gc->concepts as $mConcept)
		    <tr class="payment_concept_tr_class">
		      <th scope="row">{!!$i!!}</th>
		      <td>{!!$mConcept->name!!}</td>
		      		<?php $iTotalDiscount = 0; $bApplyDiscounts = false;?>
		    		@foreach($cDiscountxStudents as $mDiscountxStudents)
			    		@if($mConcept->id == $mDiscountxStudents->id_concept)
			    			@if($mDiscountxStudents->id_discount != $iLastindexDiscount)
						    	<?php
				    				$iTotalDiscount+= $mDiscountxStudents->amount;
								?>
						    	<?php $iLastindexDiscount = $mDiscountxStudents->id_discount;?>			    	
					    	@endif
			    		@endif	    		
			    	@endforeach
			    	<?php 
			    		$iLastindexDiscount = -1;
			    		$iamountRemaining += $iTotalDiscount;
		    		?>
		      <?php
		      	if ($iamountRemaining >= ($mConcept->amount)){
		      		$amountToPayRow = $mConcept->amount;
		      		$iamountRemaining -= $mConcept->amount;
		      		$bApplyDiscounts = true;
		      	}else{
		      		$iamountRemaining -= $iTotalDiscount;
		      		$amountToPayRow = $iamountRemaining ;
		      		$iamountRemaining = 0;
		      	}
		      	//dd($iamountRemaining);
		      ?>
		      <td>{!!"S/. ".$amountToPayRow!!}</td>
		    </tr>
		    	@if($bApplyDiscounts)
		    	@foreach($cDiscountxStudents as $mDiscountxStudents)
		    		@if($mConcept->id == $mDiscountxStudents->id_concept)
		    			@if($mDiscountxStudents->id_discount != $iLastindexDiscount)
					    	<?php $i++;?>
					    	<tr class="payment_discount_tr_class" >
					    		<th scope="row">{!!$i!!}</th>
					    		<td>{!!$mDiscountxStudents->name!!}</td>
				    			<?php
				    				$amountToPayRow = $mDiscountxStudents->amount;
							    ?>
							    <td>{!!"- S/. ".$amountToPayRow!!}</td>			    		
					    	</tr>
					    	<?php $iLastindexDiscount = $mDiscountxStudents->id_discount;?>			    	
				    	@endif
		    		@endif	    		
		    	@endforeach
		    	@endif
		    	<?php 
		    		if ($iamountRemaining==0) {
			      		break;
			      	}
		    		$i++;
	    		?>
		    @endforeach
		                  
		  </tbody>
		</table>

		<div class="form-horizontal form-label-left">
			<div class="form-group">
			    <label class="control-label col-md-9 col-sm-9 col-xs-8" for="first-name">Total: </label>
			    <div class="col-md-1 col-sm-2 col-xs-4">
			      <input readOnly id="amountToPay" name="amountToPay" required="required" class="form-control col-md-3 col-xs-3" value="{!!'S/.'.($iAmountToPay-$iamountRemaining)!!}">
			    </div>
			</div>
		</div>

		<div class="ln_solid"></div>
		<div class="form-group">
			<input name="id_student" type="hidden" value = "{!!$gc->entity_to_edit->id!!}">
			<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">Registrar pago</button>
			</div>
		</div>
	</form>

@endsection

@section('scripts')
<script >
$(document).ready(function() {	  

	document.getElementById("form").addEventListener("submit", prepareRequest);

	function prepareRequest(){		
		document.getElementById('request').innerHTML = '<input name="amountToPay" type="hidden" value ='+document.getElementById('amountToPay').value+'>';
	}

});
</script>
@endsection