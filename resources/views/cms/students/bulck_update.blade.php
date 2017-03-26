<?php
  $gc->create = true;
  $gc->select = true;
  $gc->date = true;
  $gc->default_buttons = false;
  $gc->page_name = "Carga masiva de alumnos";
  $gc->page_description = "Seleccione el aula y el archivo a cargar.";
?>
@extends('cms.templates.template')
@section('content')
{!!Form::open(['data-parsley-validate','route'=>'students.bulck_store','method'=>'POST', 'files' => true,'class'=>'form-horizontal form-label-left'])!!}             
              
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Seleccione aula</label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <select id="selected_year" name="selected_year" class="select2_single form-control">
        @foreach($gc->groups as $group)
        <option id="{!!$group->id!!}" value="{!!$group->id!!}">{!!$group->year." - ".$group->name!!}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Seleccione archivo</label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      {!! Form::file('file_excel', array('id'=>'file_excel', 'class'=>'form-control', 'data-parsley-fileinput'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'required')) !!}
    </div>
  </div>               
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                  
      <button type="submit" class="btn btn-success">Continuar</button>
    </div>
  </div>

</form>
@endsection


@section('scripts')
 <!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2();  
    });
  </script>
<!-- /select2 -->

  <script>
    
    window.Parsley
    .addValidator('fileinput', {
      requirementType: 'string',
      validateString: function(value, requirement, parsleyInstance) {

        var fileSelect = document.getElementById('file_excel');
        var files = fileSelect.files;
        var file = files[0];

        if (file.length == 0) {
            return true;
        }

        var allowedMimeTypes = requirement.replace(/\s/g, "").split(',');
        return allowedMimeTypes.indexOf(file.type) !== -1;        
      },
      messages: {
        en: 'El archivo debe ser tipo xlsx',
      }
    });
  </script>

@endsection