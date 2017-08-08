<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

  $gc->select = true;
  $gc->default_buttons = false;
  $gc->table = true;
  $gc->page_name = "Consolidado por aula $ClassRoom_name";
?>

@extends('cms.templates.template')

@section('content')            

{!!Form::open(['route'=>'reports.consolidatedDebtReport','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
  
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Seleccione Aula</label>
    <div class="col-md-6 col-sm-9 col-xs-12">
      <select required="required" id="classroom_id" name="classroom_id" class="select2_single form-control col-md-12 col-sm-12 col-xs-12">
        @foreach($cClassrooms as $classroom)
          <option id="{!!$classroom->id!!}" value="{!!$classroom->id!!}">{!!$classroom->name." - ".$classroom->year!!}</option>
        @endforeach
      </select>
    </div>
  </div>      
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <button type="submit" class="btn btn-success">Generar Reporte</button>
    </div>
  </div>

</form>

@if(count($concepts)>0)
<table id="debts_table" class="display dataTable">
  <thead>
    <tr>
      <th>Alumno</th>
      @foreach($concepts as $concept)
      <th>{!!$concept->name!!}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    <?php
      $lastName = "";      
    ?>    
    @foreach($consolidatedDebtReportGrid as $row)
      
      @if($lastName!=$row->fullname)
        <tr><td>{!!$row->fullname!!}</td>
        <?php
          $lastName = $row->fullname
        ?>
      @endif

      @if($row->debt==-1)
      @else
      <td>{!!$row->debt!!}</td>
      @endif
    @endforeach
                  
  </tbody>
</table>
@endif
@endsection


@section('scripts')
 <!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2({
      });
    });
  </script>
<!-- /select2 -->
<!-- /datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {      
    });

    $('#debts_table').DataTable( {
        autoFill: true
    } );
  </script>
@endsection