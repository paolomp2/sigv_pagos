@extends('cms.templates.template')

@section('content')

            <table id="list_table" class="display dataTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre del grupo</th>
                  <th>Año</th>
                  <th># alumnos</th>                  
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;?>
                
                @foreach($gc->groups as $group)
                <tr>

                  <th scope="row">{!!$i!!}</th>
                  <td>{!!$group->description!!}</td>
                  <td>{!!$group->year!!}</td>
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