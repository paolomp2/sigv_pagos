@extends('cms.templates.template')

@section('content')
<!-- page content -->
      <div>
        <div>          
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>{!!$gc->page_description!!}</h2>
                  
                  <div class="clearfix"></div>
                </div>                

                <div class="x_content">
                  
                  {!!Form::open(['route'=>'students.enrolling_fast','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}           
                                      
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                        
                        <a href="/students/create" class="btn btn-success">Crear alumno <i class="fa fa-angle-double-right">&nbsp</i></a>
                      </div>
                    </div>

                  </form>

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
                  
                  {!!Form::open(['route'=>'students.enrolling_fast','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}           
                  
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Aula</label>
                      <div class="col-md-6 col-sm-9 col-xs-12">
                        <select required="required" id="classroom" name="classroom" class="select2_single form-control col-md-12 col-sm-12 col-xs-12">
                          @foreach($gc->classrooms as $classroom)
                          <option value="{!!$classroom->id!!}">{!!$classroom->description!!}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                        
                        <button type="submit" class="btn btn-success">Agregar alumnos <i class="fa fa-angle-double-right">&nbsp</i></button>
                      </div>
                    </div>

                  </form>

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