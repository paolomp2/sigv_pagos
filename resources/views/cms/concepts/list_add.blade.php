<?php
  if(is_null($gc)){
    dd("Error - General Container not found");
  }
    $gc->default_buttons = false;
    $gc->add_buttons = true;
    $gc->table = true;
    $gc->page_name = "Lista de Grupos del Concepto: ".$gc->entity_to_edit->name;
    $gc->page_description = "Esta lista contiene grupos de conceptos";
    $gc->breadcrumb('concepts.list_groups.'.$gc->entity_to_edit->id_md5);
?>

@extends('cms.templates.template')

@section('content')

            <table id="list_table" class="display dataTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre del grupo</th>
                  <th># alumnos</th>                  
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;?>
                @foreach($gc->groups as $group)
                <tr>

                  <th scope="row">{!!$i!!}</th>
                  <td>{!!$group->name!!}</td>
                  
                  <td>{!!$group->num_people!!}</td>
                  
                  <td>
                    <?php 
                      if (is_null($group->deleted_at)) {
                        $route_destroy = "/".$gc->url_base."/".$gc->entity_to_edit->id_md5."/".$group->id_md5."/add_inactive/";                      
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