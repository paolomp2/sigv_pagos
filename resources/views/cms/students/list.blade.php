<?php
  if(is_null($gc)){
    dd("Error - General Container not found");
  }
    $gc->table = true;
    $gc->url_base = "students";    
    $gc->page_name = "Lista de alumnos";
    $gc->page_description = "Esta lista contiene la lista de grupos de alumnos";
    $bEnrolledFlag = true;
    $gc->breadcrumb('students');

?>

@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Apellidos</th>
      <th>Nombres</th>      
      @if($bEnrolledFlag)
      <th>Aula</th>
      @endif
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->students as $student)
    <tr>
      <td scope="row">{!!$i!!}</td>
      <td>{!!$student->last_name!!} {!!$student->maiden_name!!}</td>
      <td>{!!$student->first_name!!}</td>      
      @if($student->enrolled_flag==1)
        <td>{!!$student->Classroom->description!!}</td>                  
      @else
        <td></td>                  
      @endif
      <td>
        <?php 
          if (is_null($student->deleted_at)) {
            $route_edit = "/".$gc->url_base."/".$student->id_md5."/edit/";
            $route_destroy = "/".$gc->url_base."/".$student->id_md5."/inactive/";
            $status = "active";
          }else{

            $route_untrashed = "/".$gc->url_base."/".$student->id_md5."/untrashed/";
            $status = "inactive";
          }
        ?>

        @if($status == "active")                      
        <a href=<?php echo $route_edit;?> class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Editar </a>
        
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