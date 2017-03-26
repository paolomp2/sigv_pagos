@extends('cms.templates.template')

@section('content')

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombre</th>
      <th>Grupo de concepto</th>
      <th>Monto</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->discounts as $discount)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$discount->name!!}</td>
      <?php
      $concepts_groups = $discount->Concepts_groups()->get();
      ?>
      <td>
        @foreach($concepts_groups as $concept_groups)
          {!!$concept_groups->name!!},
        @endforeach
      </td>

      @if($discount->percentage_flag==1)
      <td>{!!$discount->amount!!}%</td>
      @else
      <td>S/.{!!$discount->amount!!}</td>
      @endif
      
      <td>
        <?php 
          if (is_null($discount->deleted_at)) {
            $route_edit = "/".$gc->url_base."/".$discount->id_md5."/edit/";
            $route_destroy = "/".$gc->url_base."/".$discount->id_md5."/inactive/";

            $status = "active";
          }else{

            $route_untrashed = "/".$gc->url_base."/".$discount->id_md5."/untrashed/";
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