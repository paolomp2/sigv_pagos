@extends('cms.templates.template')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <a href="/user_c/create" type="button" class="btn btn-info btn-lg">Crear usuario</a>
      </div>
    </div>
    <div class="clearfix"></div>


    <div class="row">

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{!!$gc->title_name!!}</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="list_table" class="display dataTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>DNI</th>
                  <th>email</th>
                  <th>fecha de cración</th>                  
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=1;?>
                @foreach($gc->users as $user)
                <tr>
                  <th scope="row">{!!$i!!}</th>
                  <td>{!!$user->name!!}</td>
                  <td>{!!$user->dni!!}</td>
                  <td>{!!$user->email!!}</td>
                  <td>{!!$user->created_at!!}</td>                  
                  <td>
                    <?php 
                      if (is_null($user->deleted_at)) {
                        $route_edit = "/user/".$user->id_md5."/edit/";
                        $route_destroy = "/user/".$user->id_md5."/inactive/";

                        $status = "active";
                      }else{

                        $route_untrashed = "/user/".$user->id_md5."/untrashed/";
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

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
@endsection


@section('scripts')

@endsection