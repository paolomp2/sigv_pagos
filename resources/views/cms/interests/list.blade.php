@extends('cms.templates.template')

@section('content')
<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombre</th>
      <th>Grupos de concepto</th>
      <th>Monto</th>
      <th>Frecuencia</th>
      <th>Veces</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1;?>
    @foreach($gc->interests as $interest)
    <tr>
      <th scope="row">{!!$i!!}</th>
      <td>{!!$interest->name!!}</td>
      <?php
      $concepts_groups = $interest->Concepts_groups()->get();
      ?>
      <td>
        @foreach($concepts_groups as $concept_groups)
          {!!$concept_groups->name!!},
        @endforeach
      </td>

      @if($interest->percentage_flag==1)
      <td>{!!$interest->amount!!}%</td>
      @else
      <td>S/.{!!$interest->amount!!}</td>
      @endif
      <td>Cada {!!$interest->recurrence!!} días</td>
      
      @if($interest->num_times!=0)
      <td>{!!$interest->num_times!!} veces</td>
      @else
      <td><button class="btn btn-round btn-warning btn-xs">Ilimitado</td>
      @endif

      <td>
        <?php 
          if (is_null($interest->deleted_at)) {
            $route_edit = "/".$gc->url_base."/".$interest->id_md5."/edit/";
            $route_destroy = "/".$gc->url_base."/".$interest->id_md5."/inactive/";
            $route_add_elements = "/".$gc->url_base."/".$interest->id_md5."/add/";
            $status = "active";
          }else{

            $route_untrashed = "/".$gc->url_base."/".$interest->id_md5."/untrashed/";
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