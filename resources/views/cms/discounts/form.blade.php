@extends('cms.templates.template')

@section('content')

@if($gc->create==true)
{!!Form::open(['route'=>'discounts.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
@else
{!!Form::open(['route'=> ['discounts.update', $gc->entity_to_edit->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
@endif

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre del descuento<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-9 col-xs-12">
      <input autofocus type="text" minlength="5" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->name.'"'!!}>
    </div>
  </div>
  
  @if($gc->create)
  @include('cms.layouts.periodo')
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Grupo de concepto al que se aplica</label>
    <div class="col-md-6 col-sm-9 col-xs-12">
      <select required="required" id="select_concept_group" name="select_concept_group" class="select2_single form-control col-md-12 col-sm-12 col-xs-12">
        @foreach($gc->concepts_groups as $concept_group)
        <option id="{!!$concept_group->monto!!}" value="{!!$concept_group->id!!}">{!!$concept_group->name!!}</option>
        @endforeach
      </select>
    </div>
  </div>
  @else
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Periodo</label>
      <div class="col-md-1 col-sm-1 col-xs-1">
        <input readonly type="text" minlength="5" id="" name="" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->year.'"'!!}>
      </div>
    </div>
  @endif
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Porcentaje/Monto fijo</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="radio">
        <label>
          <input type="radio" class="flat" value="1" checked name="radio_button_porcentage"> Porcentaje
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" class="flat" value="0" name="radio_button_porcentage"> Monto Fijo
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monto(S/.)<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input type="number" min="10" id="amount" name="amount" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->amount.'"'!!}>
    </div>
  </div>

  <div class="ln_solid"></div>

  <div class="form-group ">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">El vencimiento del dscto se dará:</label>
    <div class="input-group col-md-2 col-sm-6 col-xs-12">
      <?php
        if ($gc->entity_to_edit->days_after_expiration_date<0) {
          $days_after_expiration_date=$gc->entity_to_edit->days_after_expiration_date*-1;
        }else{
          $days_after_expiration_date=$gc->entity_to_edit->days_after_expiration_date;
        }
      ?>
      <input type="number" id="days_bef_aft" name="days_bef_aft" required="required" class="form-control" value={!!'"'.$days_after_expiration_date.'"'!!}>
      <span class="input-group-addon">días</span>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="radio">
        <label>
          <input type="radio" class="flat" value="0" @if($gc->entity_to_edit->days_after_expiration_date>=0) checked @endif name="radio_button_flag_before_after"> antes de la fecha de vencimiento
        </label>
      </div>
      <div class="radio">
        <label>
          <input type="radio" class="flat" value="1" @if($gc->entity_to_edit->days_after_expiration_date<0) checked @endif name="radio_button_flag_before_after"> después de la fecha de vencimiento
        </label>
      </div>
    </div>
  </div>               
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <a href="/discounts/" type="submit" class="btn btn-primary">Cancelar</a>
      <button type="submit" class="btn btn-success">Enviar</button>
    </div>
  </div>

</form>
@endsection


@section('scripts')
 <!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2({
      });
    });
  </script>
<!-- /select2 -->
<!-- /datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {      
    });

    $('#date_release').daterangepicker({
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
    $('#date_expiration').daterangepicker({
      dateFormat: 'yy-mm-dd',
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  </script>
@endsection