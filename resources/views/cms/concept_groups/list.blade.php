@extends('cms.templates.template')

@section('content')
<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombre</th>
      <th>Periodo</th>
      <th>Monto</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($c_gc->concept_groups as $concept_group)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$concept_group->name!!}</td>
      <td>{!!$concept_group->year!!}</td>
      <td>S/. {!!$concept_group->amount!!}</td>               
      <td>
        <?php 
          if (is_null($concept_group->deleted_at)) {
            $route_edit = "/concepts_groups/".$concept_group->id_md5."/edit/";
            $route_destroy = "/concepts_groups/".$concept_group->id_md5."/inactive/";

            $status = "active";
          }else{

            $route_untrashed = "/concepts_groups/".$concept_group->id_md5."/untrashed/";
            $status = "inactive";
          }
        ?>

        @if($status == "active")                      
        <a href=<?php echo $route_edit;?> class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Editar </a>
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Desactivar </a>
        @else
        <a href=<?php echo $route_untrashed;?> class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Restaurar </a>
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