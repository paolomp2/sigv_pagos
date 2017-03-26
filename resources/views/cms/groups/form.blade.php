@extends('cms.templates.template')

@section('content')
               
@if($gc->create==true)
{!!Form::open(['route'=>$gc->url_base.'.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
@else
{!!Form::open(['route'=> [$gc->url_base.'.update', $gc->entity_to_edit->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
@endif

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre del grupo<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-9 col-xs-12">
      <input autofocus type="text" minlength="5" maxlength="50"  id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->name.'"'!!}>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Descripción del grupo<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-9 col-xs-12">
      <input type="text" minlength="5" maxlength="150" id="description" name="description" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->description.'"'!!}>
    </div>
  </div>                            
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <a href="/{!!$gc->url_base!!}/" type="submit" class="btn btn-primary"><i class="fa fa-times">&nbsp</i> Cancelar</a>
      <button type="submit" class="btn btn-warning">Continuar &nbsp&nbsp<i class="fa fa-arrow-right">&nbsp</i></button>
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
@endsection