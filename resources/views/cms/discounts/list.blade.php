@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Grupo de Conceptos</th>
      <th>Nombre</th>
      <th>Año</th>
      <th>Monto</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->discounts as $discount)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$discount->Concepts_groups->name!!}</td>
      <td>{!!$discount->name!!}</td>
      <td>{!!$discount->year!!}</td>
      @if($discount->percentage_flag==1)
      <td>{!!$discount->amount!!} %</td>
      @else
      <td>S/. {!!$discount->amount!!}</td>
      @endif             
      <td>
        <?php 
          if (is_null($discount->deleted_at)) {
            $route_edit = "/discounts/".$discount->id_md5."/edit/";
            $route_destroy = "/discounts/".$discount->id_md5."/inactive/";
            $route_add_elements = "/discounts/".$discount->id_md5."/add/";
            $status = "active";
          }else{

            $route_untrashed = "/discounts/".$discount->id_md5."/untrashed/";
            $status = "inactive";
          }
        ?>

        @if($status == "active")                      
        <a href=<?php echo $route_edit;?> class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Editar </a>
        <a href=<?php echo $route_destroy;?> class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Desactivar </a>                    
        <a href=<?php echo $route_add_elements;?> class="btn btn-success btn-xs"><i class="fa fa-search-plus"></i> Ver grupos</a>
          
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