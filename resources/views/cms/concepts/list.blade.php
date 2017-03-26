<?php
  if(is_null($gc)){
    dd("Error - General Container not found");
  }
    $gc->table = true;
    $gc->page_name = "Lista de Conceptos";
    $gc->page_description = "Esta lista contiene los conceptos";
    $gc->breadcrumb('concepts');
?>

@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Grupo</th>
      <th>Nombre</th>
      <th>Periodo</th>
      <th>Fecha Vigencia</th>
      <th>Fecha Caducidad</th>
      <th>Monto</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->concepts as $concept)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$concept->Concept_group->name!!}</td>
      <td>{!!$concept->name!!}</td>
      <td>{!!$concept->year!!}</td>
      <td>{!!$concept->fecha_vigencia!!}</td>
      <td>{!!$concept->fecha_vencimiento!!}</td>
      <td>S/. {!!$concept->amount!!}</td>               
      <td>
        <?php 
          if (is_null($concept->deleted_at)) {
            $route_edit = "/concepts/".$concept->id_md5."/edit/";
            $route_destroy = "/concepts/".$concept->id_md5."/inactive/";
            $route_add_elements = "/concepts/".$concept->id_md5."/add/";
            $status = "active";
          }else{

            $route_untrashed = "/concepts/".$concept->id_md5."/untrashed/";
            $status = "inactive";
          }
        ?>

        @if($status == "active")                      
        <a href=<?php echo $route_edit;?> class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Editar </a>
        <!-- <a href=<?php echo $route_destroy;?> data-toggle="modal" data-target="#confirm-submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Desactivar </a>-->
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Desactivar </a>
        <a href=<?php echo $route_add_elements;?> class="btn btn-success btn-xs"><i class="fa fa-search-plus"></i> Ver grupos</a>
          
        @else
        <a href=<?php echo $route_untrashed;?> class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Activar </a>
        @endif

        
      </td>         
    </tr>
    <?php $i++;?>
    @endforeach
                  
  </tbody>
</table>



@endsection


@section('scripts')
@endsection