<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Document/title>
    {!! Html::style('assets/css/pdf.css') !!}
  </head>
  <body>
 
    <main>
      <div id="details" class="clearfix">
        <div id="invoice">
          <h1>{{ $oPayment_document->correlative_number }}</h1>
          <div class="date">{{ $oPayment_document->date_sell }}</div>
          <?php
            if (is_null($oPayment_document->Student()->first_name)) {
              $student_name = $oPayment_document->Student()->full_name;
            }else{
              $student_name = $oPayment_document->Student()->first_name." ".$oPayment_document->Student()->last_name;
            }
          ?>
          <div class="date">{{ $student_name }}</div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">        
        <tbody>

          @foreach($cPayment_document_line as $mPayment_document_line)

          <tr>
            <?php
              $name = "ERROR - - DOCUMENTTOPRINT.BLADE.PHP";
              $signal = "";    
              if ($mPayment_document_line->type_entity=='CONCEPT') {
                $name = $mPayment_document_line->getConcept()->name;
              }

              if ($mPayment_document_line->type_entity=='DISCOUNT') {
                $name = $mPayment_document_line->getDiscount()->name;
                $signal = "-";
              }

            ?>
            <td class="total">{{ $name }} </td>
            <td class="total">{{ $signal." S/.".number_format($mPayment_document_line->amount,2)}} </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"></td>
            <td >TOTAL</td>
            <td>{{ " S/.".number_format($oPayment_document->total_amount,2) }}</td>
          </tr>
        </tfoot>
      </table>
  </body>
</html>