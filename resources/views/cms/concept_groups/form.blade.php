@extends('cms.templates.template')

@section('content')
                 
@if($gc->create)
{!!Form::open(['route'=>'concepts_groups.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
@else
{!!Form::open(['route'=> ['concepts_groups.update', $c_gc->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
@endif

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre del Grupo de Conceptos<span class="required">*</span>
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