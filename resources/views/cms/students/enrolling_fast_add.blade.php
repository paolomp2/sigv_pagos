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
                  
                  {!!Form::open(['route'=>'students.enrolling_fast_store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}           
                    
                    <input id="classroom_id" name="classroom_id" type="hidden" value="{!!$gc->entity_to_edit->id!!}">
                    <input id="identifier" name="identifier" type="hidden" value="{!!$gc->entity_to_edit->identifier!!}">
                    <input id="studients_ids" name="studients_ids" type="hidden" value="">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Aula<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-9 col-xs-12">
                        <input readonly type="text" minlength="5" maxlength="50"  id="names" name="names" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->description.'"'!!}>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Capacidad<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-9 col-xs-12">
                        <input readonly type="text" minlength="5" maxlength="50"  id="last_name" name="last_name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->capacity.'"'!!}>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Matriculados<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-9 col-xs-12">
                        <input readonly type="text" minlength="5" maxlength="50"  id="maiden_name" name="maiden_name" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$gc->entity_to_edit->num_people.'"'!!}>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Cantidad de alumnos que pueden matricularse:<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-9 col-xs-12">
                        <?php $num_remainings_students = $gc->entity_to_edit->capacity - $gc->entity_to_edit->num_people; ?>
                        <input readonly type="text" minlength="5" maxlength="50"  id="num_remainings_students" name="num_remainings_students" required="required" class="form-control col-md-7 col-xs-12" value={!!'"'.$num_remainings_students.'"'!!}>
                      </div>
                    </div>

                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Seleccione alumno</label>
                      <div class="col-md-6 col-sm-9 col-xs-12">
                        <select autofocus required="required" id="student" name="student" class="select2_single form-control col-md-12 col-sm-12 col-xs-12">
                          <option value="-1">Seleccione un alumno</option>
                          @foreach($gc->students as $student)
                          <option namestudent = "{!!$student->last_name!!} {!!$student->maiden_name!!}, {!!$student->first_name!!} {!!$student->middle_name!!}" value="{!!$student->id_md5!!}">{!!$student->last_name!!} {!!$student->maiden_name!!}, {!!$student->first_name!!} {!!$student->middle_name!!}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                        
                        <button type="submit" class="btn btn-success">Registrar todos los alumnos <i class="fa fa-angle-double-right">&nbsp</i></button>
                      </div>
                    </div>
                  </form>

                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2 id="title_table_students">Lista de alumnos a registrar <small>Los alumnos no se registrarán hasta que de click en registrar</small></h2>
                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <input id="num_elements_table_students" type="hidden" value="0">
                  <table id="table_students" class="table table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Opciones</th>                        
                      </tr>
                    </thead>
                    <tbody>                      
                    </tbody>
                  </table>

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
        allowClear: true
      });

      $('#student').select2('open');

      $('#student').on('select2:select', function(evt){
        //console.log("you selected :" + $(this).val());
        if($(this).val()==-1)
          return;
        //initializing variables
        var table = document.getElementById("table_students");
        var num_students = document.getElementById("num_elements_table_students");
        var title_table = document.getElementById("title_table_students");

        //verifieding id
        var students_id_string = document.getElementById("studients_ids").value;
        var students_id_array;
        console.log("Array134: "+students_id_string);
        if(students_id_string!=null)
        {
          students_id_array = students_id_string.split(',');  
        }else{
          students_id_array= new Array();
        }

        var index_id = students_id_array.indexOf($(this).val());

        if(index_id==-1)//caso en el que no se encuentra el índice
        {
          //adding element to array
          students_id_array.push($(this).val());
          //console.log("Array148: "+students_id_array);
          //Updating num students
          num_students.value = parseInt(num_students.value) + 1;
          document.getElementById("num_elements_table_students").innerHTML = num_students.value;
          //modifing the title table
          var new_title = "Se están agregando "+num_students.value+" alumnos. DEBE DAR CLICK EN REGISTRAR PARA CONFIRMAR";
          document.getElementById("title_table_students").innerHTML = new_title;        
          //Adding new row
          var row = table.insertRow(parseInt(num_students.value));
          var cell_num = row.insertCell(0);
          var cell_name = row.insertCell(1);
          var cell_option = row.insertCell(2);
          cell_num.innerHTML = num_students.value;
          cell_name.innerHTML = evt.params.data.text;
          //adding button
          var button_delete_text = "<a href='#' value='"+$(this).val()+"' class='delete btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Quitar estudiante </a>";
          cell_option.innerHTML = button_delete_text;

          document.getElementById("studients_ids").value = students_id_array.toString();

          //updating num remaining students
          document.getElementById("num_remainings_students").value = parseInt(document.getElementById("num_remainings_students").value) - 1;
          //console.log("Array167: "+document.getElementById("studients_ids").value);
        }else{
          alert("Este alumno ya fue agregado");
        }
        //finally the option will be remove
        console.log($(this).val());
        $("#student option[value='"+$(this).val()+"']").remove();
      });
    });
    
    $('#table_students').on('click', '.delete', function(e) {
      e.preventDefault();
      //extracting ids selecteds
      var students_id_string = document.getElementById("studients_ids").value;
      var students_id_array = students_id_string.split(',');
      console.log("Value= "+$(this).attr('value'));
      var index_id = students_id_array.indexOf($(this).attr('value'));
      //deleting if the id is finded
      console.log("Index Encontrado: "+index_id);
      if (index_id > -1) {
          students_id_array.splice(index_id, 1);//The second parameter of splice is the number of elements to remove.
      }
      //updating the input of ids
      document.getElementById("studients_ids").value = students_id_array.toString();
      console.log("Array188: "+students_id_array);

      //updating the number of students
      var num_students = document.getElementById("num_elements_table_students");
      num_students.value = parseInt(num_students.value) - 1;
      //modifing the title table
      var new_title = "Se están agregando "+num_students.value+" alumnos. DEBE DAR CLICK EN REGISTRAR PARA CONFIRMAR";
      document.getElementById("title_table_students").innerHTML = new_title; 
      //removing the row
      $(this).parent().parent().remove();
      console.log("deleting198");
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