@extends('cms.templates.template')

@section('content')
                 
@if($gc->create)
{!!Form::open(['route'=>'concepts_groups.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
@else
{!!Form::open(['route'=> ['concepts_groups.update', $c_gc->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
@endif

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre del concepto<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <input autofocus type="text" minlength="5" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$c_gc->name.'"'!!}>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monto(S/.)<span class="required">*</span>
    </label>
    <div class="col-md-1 col-sm-1 col-xs-1">
      <input type="number" min="10" id="amount" name="amount" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$c_gc->amount.'"'!!}>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Descuentos asociados</label>
    <div class="col-md-6 col-sm-6 col-xs-12">

      <select name="discounts[]" id="discounts[]" class="select2_multiple form-control" multiple="multiple">
        
        @foreach($gc->discounts as $discount)

          <?php
          $selected=false;
          foreach ($gc->discounts_id as $id_selected) {
            if($discount->id==$id_selected->id_discount)
              $selected=true;
          }
          ?>
          <option @if($selected) selected @endif value="{!!$discount->id!!}" >{!!$discount->name!!}</option>
        
        @endforeach
      </select>
    </div>
  </div>     
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Intereses asociados</label>
    <div class="col-md-6 col-sm-6 col-xs-12">

      <select name="interest[]" id="interest[]" class="select2_multiple form-control" multiple="multiple">
        
        @foreach($gc->interests as $interest)

          <?php
          $selected=false;
          foreach ($gc->interests_id as $id_selected) {
            if($interest->id==$id_selected->id_interest)
              $selected=true;
          }
          ?>
          <option @if($selected) selected @endif value="{!!$interest->id!!}" >{!!$interest->name!!}</option>
        
        @endforeach
      </select>
    </div>
  </div>
  @include('cms.layouts.periodo')
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <a href="/concepts_groups/" type="submit" class="btn btn-primary">Cancelar</a>
      <button type="submit" class="btn btn-success">Enviar</button>
    </div>
  </div>

</form>

@endsection


@section('scripts')
<!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_multiple").select2({
        
      });
    });
  </script>
@endsection