@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Num. Estudiantes</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->families_members as $family_member)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$family_member->first_name!!} {!!$family_member->middle_name!!}</td>
      <td>{!!$family_member->last_name!!} {!!$family_member->maiden_name!!}</td>
      @if($family_member->num_students==0)
      <td><button type="button" class="btn btn-danger btn-xs">Sin alumno</button></td>
      @else
      <td>{!!$family_member->num_students!!}</td>
      @endif
      <td>
        <?php 
          if (is_null($family_member->deleted_at)) {
            $route_edit = "/".$gc->url_base."/".$family_member->id_md5."/edit/";
            $route_destroy = "/".$gc->url_base."/".$family_member->id_md5."/inactive/";
            $route_add_elements = "/".$gc->url_base."/".$family_member->id_md5."/add/";
            $status = "active";
          }else{

            $route_untrashed = "/".$gc->url_base."/".$family_member->id_md5."/untrashed/";
            $status = "inactive";
          }
        ?>

        @if($status == "active")                      
        <a href=<?php echo $route_edit;?> class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Editar </a>
          @if($family_member->num_students==0)
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Desactivar </a>
          @endif
          @if($gc->add_elements)
        <a href=<?php echo $route_add_elements;?> class="btn btn-round btn-success"><i class="fa fa-arrow-circle-up"></i> {!!$gc->msg_add_elements!!}</a>
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