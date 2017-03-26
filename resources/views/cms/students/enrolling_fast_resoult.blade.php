
@extends('cms.templates.template')

@section('content')
<!-- page content -->
      <div>
        <div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>{!!$gc->page_description!!}</h2>
                  
                  <div class="clearfix"></div>
                </div>                

                <div class="x_content">
                    
	                <div class="bs-example" data-example-id="simple-jumbotron">
					  <div class="jumbotron">
					    <h1>Se han matriculado {!!$gc->num_students!!} estudiantes</h1>
					    <p>Los estudiantes han sido registrados en las respectivas aulas. Puede ir a Alumnos/Alumnos matriculados para ver y editar los perfiles respectivos.</p>
					  </div>
					</div>

	                <div class="ln_solid"></div>
	                <div class="form-group">
	                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                        
	                    <button type="submit" class="btn btn-success">Continuar <i class="fa fa-angle-double-right">&nbsp</i></button>
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