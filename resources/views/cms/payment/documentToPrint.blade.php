<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Documento de Pago</title>
    {!!Html::style('cms/css/pdf.css')!!}
  </head>
  <body> 
    <div class="Document">
      @foreach($cPayment_document as $oPayment_document)
      <div id="DocumentHeader">
          <div id="EmissionDate">
            {{ $oPayment_document->date_sell }}                        
          </div>
          <div id="Scale">
            Regular
          </div>    
          <div id="CorrelativeNumber">
            {{ $oPayment_document->correlative_number}}
          </div>
          <div id="StudentName">
            <?php
              if (is_null($oPayment_document->Student()->first_name)) {
                $student_name = $oPayment_document->Student()->full_name;
              }else{
                $student_name = $oPayment_document->Student()->first_name." ".$oPayment_document->Student()->last_name;
              }
            ?>
            {{ $student_name }}
          </div>
          <div id="StudentCode">
            {{ $oPayment_document->Student()->id +1002358 }}
          </div>
          <div id="ExpirationDate">
            {{ $oPayment_document->date_sell }}
          </div>
      </div>

      <div class="DocumentBody">

        <table id = "DocumentLines">        
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
              <td class="DocumentLinesConcept">{{ $name }} </td>
              <td class="DocumentLinesAmount">{{ $signal." S/.".number_format($mPayment_document_line->amount,2)}} </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td>{{ " S/.".number_format($oPayment_document->total_amount,2) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
      @endforeach    
    </div>
  </body>
</html>