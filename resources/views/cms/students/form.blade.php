@extends('cms.templates.template')

@section('content')

<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"></label>
  <div class="profile_img" class="col-md-6 col-sm-9 col-xs-12">
    <!-- end of image cropping -->
    <div id="crop-avatar">
      <!-- Current avatar -->
      <div class="avatar-view" title="Change the avatar">
        <img src="{!!asset('images/students/generic.png')!!}" alt="Avatar">
      </div>

      <!-- Cropping modal -->
      <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            {!!Form::open(['route'=>'students.picture','method'=>'POST', 'class'=>'avatar-form', 'enctype'=>'multipart/form-data'])!!}
              <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">Cambiar imagen</h4>
              </div>
              <div class="modal-body">
                <div class="avatar-body">

                  <!-- Upload image and data -->
                  <div class="avatar-upload">
                    <input class="student_id" name="student_id" type="hidden" value="{!!$gc->entity_to_edit->id!!}">
                    <input class="avatar-src" name="avatar_src" type="hidden">
                    <input class="avatar-data" name="avatar_data" type="hidden">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="avatarInput">Seleccione imagen</label>
                    <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                  </div>

                  <!-- Crop and preview -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="avatar-wrapper"></div>
                    </div>
                  </div>

                  <div class="ln_solid"></div>
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button class="btn btn-primary" data-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn btn-primary btn-success" type="submit">Subir</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal -->

      <!-- Loading state -->
      <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
    </div>
    <!-- end of image cropping -->

  </div>
</div>

  @if($gc->create==true)
  {!!Form::open(['route'=>$gc->url_base.'.store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}           
  @else
  {!!Form::open(['route'=> [$gc->url_base.'.update', $gc->entity_to_edit->id],'method'=>'PUT', 'class'=>'form-horizontal form-label-left'])!!}
  @endif

    <input class="student_id" name="student_id" type="hidden" value="{!!$gc->entity_to_edit->id!!}">
  
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombres<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input autofocus type="text" minlength="5" maxlength="50"  id="names" name="names" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->first_name.' '.$gc->entity_to_edit->middle_name.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Apellido Paterno<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input type="text" minlength="5" maxlength="50"  id="last_name" name="last_name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->last_name.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Apellido Materno<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input type="text" minlength="5" maxlength="50"  id="maiden_name" name="maiden_name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->maiden_name.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Sexo</label>
      <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="radio">
          <label>
            <input required="required" type="radio" class="flat" value="1" @if($gc->entity_to_edit->gender==1) checked @endif name="gender"> Masculino
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" class="flat" value="0" @if($gc->entity_to_edit->gender==0) checked @endif name="gender"> Femenino
          </label>
        </div>
      </div>
    </div>                     
    
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de nacimiento<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="controls">                          
            <input data-inputmask="'mask' : '****/**/**'" required="required" type="text" class="form-control has-feedback-left" id="date_birthday" name="date_birthday" placeholder="Fecha de nacimiento" value={!!'"'.$gc->entity_to_edit->birthday.'"'!!}>
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">DNI<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input data-inputmask="'mask' : '9-9-9-9-9-9-9-9'" type="text" min="1111111" max="99999999" id="dni" name="dni" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->dni.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">Dep. / Prov. / Dist</label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <select required="required" id="ubigeo" name="ubigeo" class="select2_single form-control col-md-12 col-sm-12 col-xs-12">
          @foreach($gc->ubigeos as $ubigeo)
          <option @if($ubigeo->id==$gc->entity_to_edit->ubigeo_id) selected @endif value="{!!$ubigeo->id!!}">{!!$ubigeo->summary!!}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dirección<span class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input autofocus type="text" minlength="5" maxlength="100"  id="address" name="address" required="required" class="form-control col-md-7 col-xs-12" placeholder="Calle, número" value={!!'"'.$gc->entity_to_edit->address.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Teléfono fijo
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input data-inputmask="'mask' : '99-9999999'" type="text" min="111111111" max="999999999" id="phone" name="phone" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->phone.'"'!!}>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Celular
      </label>
      <div class="col-md-6 col-sm-9 col-xs-12">
        <input data-inputmask="'mask' : '9-9-9-9-9-9-9-9-9'" type="text" min="111111111" max="999999999" id="cellphone" name="cellphone" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->cellphone.'"'!!}>
      </div>
    </div>

    

    <div class="ln_solid"></div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <a href="/{!!$gc->url_base!!}/" type="submit" class="btn btn-primary"><i class="fa fa-times">&nbsp</i> Cancelar</a>
        <button type="submit" class="btn btn-success"><i class="fa fa-save">&nbsp</i> Guardar</button>
      </div>
    </div>

  </form>

</div>
@endsection


@section('scripts')

<!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2({
      });
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

<!-- /datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#date_birthday').daterangepicker({
        "singleDatePicker": true,
        calender_style: "picker_2",
        format: "YYYY-MM-DD",
      }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
      });
    });
  </script>

@endsection