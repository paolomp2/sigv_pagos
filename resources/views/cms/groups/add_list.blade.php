@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombre</th>
      <th>Aula</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->students as $student)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$student->full_name!!}</td>
      <td>{!!$student->classroom->description!!}</td>

      <td>
        <?php 
          if (is_null($student->deleted_at)) {            
            $route_destroy = "/".$gc->url_base."/".$gc->entity_to_edit->id_md5."/".$student->id_md5."/add_inactive/";
            $status = true;
          }else{

            $route_untrashed = "/".$gc->url_base."/".$student->id_md5."/untrashed/";
            $status = false;
          }
        ?>

        @if($status)
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o">&nbsp</i> Desactivar </a>
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