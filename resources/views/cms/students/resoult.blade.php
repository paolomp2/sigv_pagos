@extends('cms.templates.template')

@section('content')
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="page-title">
            <div class="title_right">
              <h3>{!!$gc->page_name!!}</h3>
            </div>
            <div class="title_right">
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>{!!$gc->page_description!!}</h2>
                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="form-horizontal form-label-left">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name"># de alumnos repetidos<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-12 col-xs-12">
                        <input readonly type="number" min="10" id="amount" name="amount" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$b_c->num_students_aux.'"'!!}>
                      </div>
                    </div>                    
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="/students/bulck/bulck" type="submit" class="btn btn-primary">Insertar nuevos alumnos</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
@endsection


@section('scripts')
 <!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2(); 
      //capturar select
      var e = document.getElementById("select_concept_group");
      //capturar monto del grupo
      var amount = e.options[e.selectedIndex].id;
      //setear valor en el campo de text
      document.getElementById("amount").value = amount;     
    });
    function amountSelect() {
      //capturar select
      var e = document.getElementById("select_concept_group");
      //capturar monto del grupo
      var amount = e.options[e.selectedIndex].id;
      //setear valor en el campo de text
      document.getElementById("amount").value = amount;
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