<?php
  $gc->create = true;
  $gc->table = true;        
  $gc->page_name = "la importación fue exitosa";
  $gc->page_description = "Continuar para buscar alumnos repetidos";
?>
@extends('cms.templates.template')

@section('content')
            <div class="form-horizontal form-label-left">
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <a href="/students/" type="submit" class="btn btn-primary">Ir a listado de Alumnos</a>
                </div>
              </div>
            </div>
@endsection


@section('scripts') 

@endsection