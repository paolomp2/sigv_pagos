@extends('cms.templates.template')

@section('content')           

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombre</th>
      <th>Relación</th>                  
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->studentsXfamily_members as $studentXfamily_member)
    <tr>

      <th scope="row">{!!$i!!}</th>
      <td>{!!$studentXfamily_member->Student->first_name!!} {!!$studentXfamily_member->Student->middle_name!!}, {!!$studentXfamily_member->Student->last_name!!} {!!$studentXfamily_member->Student->maiden_name!!}</td>
      
      <td>{!!$studentXfamily_member->Relationship->name!!}</td>
      
      <td>
        <?php 
          if (is_null($studentXfamily_member->deleted_at)) {
            $route_destroy = "/".$gc->url_base."/".$studentXfamily_member->id_md5."/add_inactive/";                      
          }
        ?>
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Eliminar </a>                    
        
      </td>         
    </tr>
    <?php $i++;?>
    @endforeach
                  
  </tbody>
</table>
@endsection


@section('scripts')

@endsection