@extends('cms.templates.template')

@section('content')
    
@if($gc->create==true)
{!!Form::open(['route'=>'concepts.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}             
@else
{!!Form::open(['route'=> ['concepts.update', $cc->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
@endif

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre del concepto<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <input autofocus type="text" minlength="5" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$cc->name.'"'!!}>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Grupo de Conceptos</label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <select onchange="amountSelect()" id="select_concept_group" name="select_concept_group" class="select2_single form-control">
        @foreach($cc->concept_groups as $concept_group)
        <option id="{!!$concept_group->amount!!}" 
          year={!!$concept_group->year!!} 
          @if($gc->entity_to_edit->id_concepto_grupo==$concept_group->id) 
            selected 
          @endif 
          value="{!!$concept_group->id!!}">{!!$concept_group->year!!} - {!!$concept_group->name!!}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de emisión<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <div class="controls">                          
          <input required="required" type="text" class="form-control has-feedback-left" id="date_release" name="date_release" placeholder="Fecha de emisión" aria-describedby="inputSuccess2Status2" value={!!'"'.$cc->fecha_vigencia.'"'!!}>
          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de vencimiento<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <div class="controls">                          
          <input required="required" type="text" class="form-control has-feedback-left" id="date_expiration" name="date_expiration" placeholder="Fecha de expiración" aria-describedby="inputSuccess2Status2"  value={!!'"'.$cc->fecha_vencimiento.'"'!!}>
          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
      </div>
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Monto(S/.)<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <input type="number" min="10" id="amount" name="amount" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$cc->amount.'"'!!}>
    </div>
  </div>                    
  
  <div class="ln_solid"></div>
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
      <a href="/concepts/" type="submit" class="btn btn-primary">Cancelar</a>
      <button type="submit" class="btn btn-success">Enviar</button>
    </div>
  </div>

</form>
@endsection


@section('scripts')
 <!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2(); 
      //capturar select
      var e = document.getElementById("select_concept_group");
      //capturar amount del grupo
      var amount = e.options[e.selectedIndex].id;
      //setear valor en el campo de text
      document.getElementById("amount").value = amount;     
    });
    function amountSelect() {
      //capturar select
      var e = document.getElementById("select_concept_group");
      //capturar monto del grupo
      var amount = e.options[e.selectedIndex].id;
      //capturar año de vigencia del grupo
      var year = $('option:selected', this).attr('year');
      console.log(year);
      //setear valor en el campo de text
      document.getElementById("amount").value = amount;
      //setear valor en el campo del año
      document.getElementById("year").value = year;
    }
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