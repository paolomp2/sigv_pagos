<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Documento de Pago</title>
    {!!Html::style('cms/css/pdf.css')!!}
  </head>
  <body> 
    @foreach($cPayment_document as $oPayment_document)
    <div id="Document">    
      <div id="DocumentHeader">
          <div id="EmissionDate">
            {{ $oPayment_document->date_sell }}                        
          </div>
          <div id="Scale">
            Regular
          </div>    
          <div id="CorrelativeNumber">
            {{ $oPayment_document->id_md5}}
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
          <div id="ClassroomName">
            <?php             
                $classroom_name = $oPayment_document->Student()->Classroom->name;
            ?>
            {{ $classroom_name }}
          </div>
          <div id="StudentCode">
            {{ $oPayment_document->Student()->id +1002358 }}
          </div>
          <div id="ExpirationDate">
            {{ $oPayment_document->date_sell }}
          </div>
      </div>

      <div id = "DocumentBody">

        <table id = "DocumentLines">        
          <tbody>
            <?php $cPayment_document_line = $oPayment_document->Lines(); ?>
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
              <td id="DocumentLinesConcept">{{ $name }} </td>
              <td id="DocumentLinesAmount">{{ $signal." S/.".number_format($mPayment_document_line->amount,2)}} </td>
            </tr>
            @endforeach
          </tbody>          
        </table>
        <div id = "DocumentTotal">
          {{ " S/.".number_format($oPayment_document->total_amount,2) }}
        </div>
      </div>
      
    </div>
    @endforeach 
  </body>
</html>