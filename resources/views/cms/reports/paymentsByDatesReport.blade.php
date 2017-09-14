<?php
  $gc->select           = true;
  $gc->default_buttons  = false;
  $gc->table            = true;
  $gc->date             = true;
  $gc->page_name = "Pagos por fechas";
  
?>

@extends('cms.templates.template')

@section('content')            

{!!Form::open(['route'=>'reports.paymentsByDatesReport','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
  
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha inicio<span class="required">*</span>
    </label>
    <div class="col-md-2 col-sm-12 col-xs-12">
      <div class="controls">                          
          <input required="required" type="text" class="form-control has-feedback-left" id="dateFrom" name="dateFrom" placeholder="Fecha inicio" aria-describedby="inputSuccess2Status2" value="{!!$dtMinDate!!}" >
          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha fin<span class="required">*</span>
    </label>
    <div class="col-md-2 col-sm-12 col-xs-12">
      <div class="controls">                          
          <input required="required" type="text" class="form-control has-feedback-left" id="dateTo" name="dateTo" placeholder="Fecha fin" aria-describedby="inputSuccess2Status2" value="{!!$dtMaxDate!!}" >
          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
      </div>
    </div>
  </div>     
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <button type="submit" class="btn btn-success">Generar Reporte</button>
    </div>
  </div>

</form>

@if(count($gc->payment_documents)>0)
<table id="list_table" class="display dataTable">
  <thead>
    <tr>
      <th>Fecha / Hora</th>
      <th>Correlativo</th>
      <th>Alumno</th>
      <th>Monto</th>
      <th>Estado</th>
      <th>Opciones</th>
    </tr> 
  </thead>
  <tbody>
    <?php
      $lastName = "";      
    ?>    
    @foreach($gc->payment_documents as $row)
      <tr>
        <td>{!!$row->date_sell!!}</td>
        <td>{!!$row->correlative_number!!}</td>
        <td>{!!$row->Student()->full_name!!}</td>
        <td>{!!$row->total_amount!!}</td>
        <?php $bStatus = false;?>
        
        @if($row->status == config('CONSTANTS.CREATED'))
        <?php $bStatus = true;?>
        <td>Creado</td>
        @endif
        
        @if($row->status == config('CONSTANTS.PAID_OUT'))
        <?php $bStatus = true;?>
        <td>Pagado</td>
        @endif
        
        @if($row->status == config('CONSTANTS.ANULLATED'))
        <?php $bStatus = true;?>
        <td>Anulado</td>
        @endif

        @if($bStatus == false)        
        <td>Not definded</td>
        @endif
        <td>
          <a href={!!"/Payment/show_document/".$row->id_md5!!} class="btn btn-primary btn-xs"><i class="fa fa-see"></i> Ver </a> 
        </td>
      </tr>

      
    @endforeach
                  
  </tbody>
</table>
@endif
@endsection


@section('scripts')
<!-- /datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {      
    });

    $('#dateFrom').daterangepicker({
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
    $('#dateTo').daterangepicker({
      dateFormat: 'yy-mm-dd',
      "singleDatePicker": true,
      calender_style: "picker_2",
      format: "YYYY-MM-DD",
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  </script>
@endsection