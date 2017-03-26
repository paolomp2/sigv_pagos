@extends('cms.templates.template')

@section('content')
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="page-title">
            <div class="title_right">
              <h3>{!!$fuc->page_name!!}</h3>
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
                  <h2>Ingrese los campos requeridos</h2>
                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  
                    @if($fuc->create)
                    {!!Form::open(['data-parsley-validate','route'=>'user_c.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}
                    @else
                    {!!Form::open(['data-parsley-validate','route'=> ['user_c.update', $fuc->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
                    @endif
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" placeholder={!!'"'.$fuc->name_placeholder.'"'!!} value={!!'"'.$fuc->name.'"'!!}>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">e-mail <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12" placeholder={!!'"'.$fuc->email_placeholder.'"'!!} value={!!'"'.$fuc->email.'"'!!}>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">DNI <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="dni" name="dni" required="required" class="form-control col-md-7 col-xs-12" placeholder={!!'"'.$fuc->dni_placeholder.'"'!!} value={!!'"'.$fuc->dni.'"'!!}>
                      </div>
                    </div>
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="/active_users/" type="submit" class="btn btn-primary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Enviar</button>
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

@endsection