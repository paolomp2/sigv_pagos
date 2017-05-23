<?php
  $gc->select = true;
  $gc->default_buttons = false;
  $gc->table = true;
  $gc->page_name = "Ajuste de Pagos por Alumno por Aula";
  
?>

@extends('cms.templates.template')

@section('content')            

{!!Form::open(['route'=>'updatePayments.ShowStudentsDebts','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
  
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Seleccione Aula</label>
    <div class="col-md-6 col-sm-9 col-xs-12">
      <select required="required" id="classroom_id" name="classroom_id" class="select2_single form-control col-md-12 col-sm-12 col-xs-12">
        @foreach($gc->classrooms as $classroom)
          <option id="{!!$classroom->id!!}" value="{!!$classroom->id!!}">{!!$classroom->name." - ".$classroom->year!!}</option>
        @endforeach
      </select>
    </div>
  </div>      
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <button type="submit" class="btn btn-success">Mostrar Alumnos</button>
    </div>
  </div>

</form>

@if(count($gc->concepts)>0)

<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>Alumno</th>
      @foreach($gc->concepts as $concept)
      <th>{!!$concept->name!!}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    <?php
      $lastName = "";      
    ?>    
    @foreach($gc->consolidatedDebtReportGrid as $row)
      @if($lastName!=$row->fullname)
        <tr><td>{!!$row->fullname!!}</td>
        <?php
          $lastName = $row->fullname
        ?>
      @endif
      @if($row->debt<0)
        <td>-</td>
      @else
      <td>
        <input autofocus type="text" minlength="1" id="names" name="names" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$row->debt.'"'!!}>
      </td>
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

    $('#date_release').daterangepicker({
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
    $('#date_expiration').daterangepicker({
      dateFormat: 'yy-mm-dd',
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  </script>
@endsection