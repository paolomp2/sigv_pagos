@extends('cms.templates.template')

@section('content')

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>{!!$gc->page_description!!}</h2>
                  
                  <div class="clearfix"></div>
                </div>                

                <div class="x_content">
                  
                  {!!Form::open(['route'=>$gc->url_base.'.add_store','method'=>'POST', 'class'=>'form-horizontal form-label-left'])!!}           
                    
                    <input id="entity_to_edit_id" name="entity_to_edit_id" type="hidden" value="{!!$gc->entity_to_edit->id!!}">
                    <input id="elements_id" name="elements_id" type="hidden" value="">
                    
                    <input hidden type="text" minlength="5" maxlength="50"  id="num_remainings_elements" name="num_remainings_elements"  value="1000">

                    @include('cms.layouts.combobox_groups_students')

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="/{!!$gc->url_base!!}/{!!$gc->entity_to_edit->id_md5!!}/add" type="submit" class="btn btn-primary"><i class="fa fa-times">&nbsp</i> Cancelar</a>                    
                        <button type="submit" class="btn btn-success">Confirmar <i class="fa fa-angle-double-right">&nbsp</i></button>
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
                  <h2 id="title_table">Lista de Grupos de alumnos a registrar <small>Los grupos no se registrarán hasta que de click en registrar</small></h2>
                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <input id="num_elements" type="hidden" value="0">
                  <table id="table" class="table table-hover">
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
@endsection


@section('scripts')

<!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2({
        allowClear: true
      });

      $('#select_student_group').select2('open');

      $('#select_student_group').on('select2:select', function(evt){
        //console.log("you selected :" + $(this).val());

        //initializing variables
        var table = document.getElementById("table");
        var num_elements = document.getElementById("num_elements");
        var title_table = document.getElementById("title_table");

        //verifieding id
        var students_id_string = document.getElementById("elements_id").value;
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
          num_elements.value = parseInt(num_elements.value) + 1;
          document.getElementById("num_elements").innerHTML = num_elements.value;
          //modifing the title table
          var new_title = "Se están agregando "+num_elements.value+" grupos. DEBE DAR CLICK EN REGISTRAR PARA CONFIRMAR";
          document.getElementById("title_table").innerHTML = new_title;        
          //Adding new row
          var row = table.insertRow(parseInt(num_elements.value));
          var cell_num = row.insertCell(0);
          var cell_name = row.insertCell(1);
          var cell_option = row.insertCell(2);
          cell_num.innerHTML = num_elements.value;
          cell_name.innerHTML = evt.params.data.text;
          //adding button
          var button_delete_text = "<a href='#' value='"+$(this).val()+"' class='delete btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Remover</a>";
          cell_option.innerHTML = button_delete_text;

          document.getElementById("elements_id").value = students_id_array.toString();

          //updating num remaining students
          document.getElementById("num_remainings_elements").value = parseInt(document.getElementById("num_remainings_elements").value) - 1;
          //console.log("Array167: "+document.getElementById("elements_id").value);
        }else{
          alert("Este alumno ya fue agregado");
        }
        
      });
    });
    
    $('#table').on('click', '.delete', function(e) {
      e.preventDefault();
      //extracting ids selecteds
      var students_id_string = document.getElementById("elements_id").value;
      var students_id_array = students_id_string.split(',');
      console.log("Value= "+$(this).attr('value'));
      var index_id = students_id_array.indexOf($(this).attr('value'));
      //deleting if the id is finded
      console.log("Index Encontrado: "+index_id);
      if (index_id > -1) {
          students_id_array.splice(index_id, 1);//The second parameter of splice is the number of elements to remove.
      }
      //updating the input of ids
      document.getElementById("elements_id").value = students_id_array.toString();
      console.log("Array188: "+students_id_array);

      //updating the number of students
      var num_elements = document.getElementById("num_elements");
      num_elements.value = parseInt(num_elements.value) - 1;
      //modifing the title table
      var new_title = "Se están agregando "+num_elements.value+" alumnos. DEBE DAR CLICK EN REGISTRAR PARA CONFIRMAR";
      document.getElementById("title_table").innerHTML = new_title; 
      //removing the row
      $(this).parent().parent().remove();
      console.log("deleting198");
    });

  </script>
<!-- /select2 -->

@endsection