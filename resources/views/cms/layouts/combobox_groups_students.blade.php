
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Grupo de estudiantes</label>
  <div class="col-md-6 col-sm-12 col-xs-12">
    <select id="select_student_group" name="select_student_group" class="select2_single form-control">
	<option value="-1">Seleccione un grupo alumno</option>
      @foreach($gc->groups_students as $group_student)
      <option value="{!!$group_student->id_md5!!}">{!!$group_student->year!!} - {!!$group_student->description!!}</option>
      @endforeach      
    </select>
  </div>
</div>