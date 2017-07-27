@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Clasificación</th>
      <th>Nombre</th>
      <th>year</th>
      <th>Capacidad</th>
      <th>Matriculados</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->classrooms as $classroom)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$classroom->description!!}</td>
      <td>{!!$classroom->name!!}</td>
      <td>{!!$classroom->year!!}</td>
      <td>{!!$classroom->capacity!!}</td>
      <td>{!!$classroom->num_people!!}</td>          
      
      <td class="td_align_right">
        <?php 
          if (is_null($classroom->deleted_at)) {
            $route_edit = "/".$gc->url_base."/".$classroom->id_md5."/edit/";
            $route_destroy = "/".$gc->url_base."/".$classroom->id_md5."/inactive/";
            $route_add_elements = "/".$gc->url_base."/".$classroom->id_md5."/add/";
            $status = "active";
          }else{

            $route_untrashed = "/".$gc->url_base."/".$classroom->id_md5."/untrashed/";
            $status = "inactive";
          }
        ?>

        @if($status == "active")                      
        <a href=<?php echo $route_edit;?> class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Editar </a>
        @if($classroom->num_people==0)
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Desactivar </a>
        @endif
        @if($gc->add_elements &&  $classroom->num_people>0)
        <a href=<?php echo $route_add_elements;?> class="btn btn-success btn-xs"><i class="fa fa-list">&nbsp</i> Ver alumnos</a>
        @endif
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