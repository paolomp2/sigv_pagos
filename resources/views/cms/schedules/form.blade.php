@extends('cms.templates.template')

@section('content')

  @if($gc->create==true)
  {!!Form::open(['route'=>$gc->url_base.'.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
  @else
  {!!Form::open(['route'=> [$gc->url_base.'.update', $gc->entity_to_edit->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
  @endif
  
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre del cronograma<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input autofocus type="text" minlength="5" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->name.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Descripción del cronograma<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input type="text" minlength="5" id="description" name="description" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->name.'"'!!}>
      </div>
    </div>

    @include('cms.layouts.combobox_groups_students')
                  
    
    <div class="ln_solid"></div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <a href="/concepts/" type="submit" class="btn btn-primary"><i class="fa fa-times">&nbsp</i>Cancelar</a>
        <button type="submit" class="btn btn-success"><i class="fa fa-save">&nbsp</i>Guardar y agregar conceptos</button>
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