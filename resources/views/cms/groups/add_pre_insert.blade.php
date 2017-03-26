@extends('cms.templates.template')

@section('content')
<!-- page content -->    
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>{!!$gc->page_description!!}</h2>
                  
                  <div class="clearfix"></div>
                </div>                

                <div class="x_content">
                  
                  <div class="form-horizontal form-label-left" >
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                        
                        <a href="/{!!$gc->url_base!!}/{!!$gc->entity_to_edit->id_md5!!}/add_elements_students" class="btn btn-success">Insertar por alumnos <i class="fa fa-angle-double-right">&nbsp</i></a>
                      </div>
                    </div>

                  </div>

                </div>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>{!!$gc->page_description_2!!}</h2>
                  
                  <div class="clearfix"></div>
                </div>                

                <div class="x_content">
                  
                  <div class="form-horizontal form-label-left">
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                        
                        <a href="/{!!$gc->url_base!!}/{!!$gc->entity_to_edit->id_md5!!}/add_elements_groups" class="btn btn-success">Insertar por grupos <i class="fa fa-angle-double-right">&nbsp</i></a>
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
      $(".select2_single").select2({
      });
      $('#classroom').select2('open');
    });

    
  </script>
<!-- /select2 -->

<!-- input_mask -->
  <script>
    $(document).ready(function() {
      $(":input").inputmask();
    });
  </script>
  <!-- /input mask -->


@endsection