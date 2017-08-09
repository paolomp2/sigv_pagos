<?php
use sigc\Http\Containers\generalContainer;

$gc = new generalContainer;

$gc->page_name = "Boleta: ".$oDocument_Header->correlative_number;
$gc->default_buttons = false;
$gc->add_buttons = false;
?>

@extends('cms.templates.template')

@section('content')
<div class="form-horizontal form-label-left"> 
  
  <div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
    </div>
  </div>

  <?php
      $student_name = $oDocument_Header->Student()->full_name;
  ?>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-6" for="first-name">Alumno :
    </label>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <input type="text" minlength="5" id="document_date" name="document_date" readonly="" class="form-control col-md-7 col-xs-6" value={!!$student_name!!}>
    </div>
  </div>


  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-6" for="first-name">Fecha de Pago :
    </label>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <input type="text" minlength="5" id="document_date" name="document_date" readonly="" class="form-control col-md-7 col-xs-6" value={!!$oDocument_Header->date_sell!!}>
    </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-6" for="first-name">Monto Total del Documento :
    </label>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <input type="text" minlength="5" id="document_amount" name="document_amount" readonly="" class="form-control col-md-7 col-xs-6" value={{" S/.".number_format($oDocument_Header->total_amount,2)}}
    </div>

    
  </div> 

  <div class="ln_solid"></div>

  <table id="list_table" class="display dataTable">
    <thead>
      <tr>
        <th>#</th>
        <th>Concepto</th>
        <th>Monto</th>
      </tr>
    </thead>
    <tbody>
    <?php 
      $i=1;
    ?>
    @foreach($cDocument_Body as $document_line)
      <tr>
        <th scope="row" class="col-md-2 col-sm-2 col-xs-2">{!!$i!!}</th>

        <?php
          $name = "ERROR - - DOCUMENTTOPRINT.BLADE.PHP";
          $signal = "";    
          if ($document_line->type_entity=='CONCEPT') {
            $name = $document_line->getConcept()->name;
          }

          if ($document_line->type_entity=='DISCOUNT') {
            $name = $document_line->getDiscount()->name;
            $signal = "-";
          }

          if ($document_line->type_entity=='INTEREST') {
            $name = $document_line->getInterest()->name;
          }

        ?>

        <td class="col-md-3 col-sm-3 col-xs-3">{!!$name!!}</td>
        <td class="col-md-2 col-sm-2 col-xs-2">{{ $signal." S/.".number_format($document_line->amount,2)}} </td>
        
      </tr>
      <?php 
        $i++;
      ?>
    @endforeach
    </tbody>
  </table>
</div>


@endsection

@section('scripts')

@endsection