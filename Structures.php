
INPUT
<div class="col-md-6 col-sm-9 col-xs-12">
  <input autofocus type="text" minlength="5" maxlength="50"  id="name" name="name" class="form-control col-md-7 col-xs-12" placeholder="Dejar en blanco y se autoasignará una letra" value={!!'"'.$gc->entity_to_edit->name.'"'!!}>
</div>

SELECT

<div class="col-md-6 col-sm-12 col-xs-12">
  <select onchange="ChangeID()"  id="student" name="student" class="select2_single form-control">
    @foreach($gc->students as $student)
    	<option value="{!!$student->id_md5!!}">
    		{!!$student->last_name!!}{!!" "!!}{!!$student->maiden_name!!}{!!","!!}
    		{!!$student->first_name!!}{!!" "!!}{!!$student->middle_name!!}
		</option>
    @endforeach
  </select>
</div>

BUTTONS

	SAVE

	<div class="form-group">
		<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
		  <button type="submit" class="btn btn-success">Guardar</button>
		</div>
	</div>

DATETIME

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Fecha de emisión<span class="required">*</span>
    </label>
    <div class="col-md-6 col-sm-12 col-xs-12">
      <div class="controls">                          
          <input required="required" type="text" class="form-control has-feedback-left" id="date_release" name="date_release" placeholder="Fecha de emisión" aria-describedby="inputSuccess2Status2" value={!!'"'.$cc->fecha_vigencia.'"'!!}>
          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                  
      </div>
    </div>
  </div>

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