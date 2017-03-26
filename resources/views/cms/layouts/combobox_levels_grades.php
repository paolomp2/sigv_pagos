
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Nivel</label>
  <div class="col-md-6 col-sm-12 col-xs-12">
    <select onchange="change_level()" required="required" id="level" name="level" class="select2_single form-control">
      <option selected value="1">Inicial</option>
      <option value="2">Primaria</option>
      <option value="3">Secundaria</option>      
    </select>
  </div>
</div>

<div id="div_initial" class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Grado de Iniciar</label>
  <div class="col-md-6 col-sm-12 col-xs-12">
    <select required="required" id="initial_grade" name="initial_grade" class="select2_single form-control">
      
      <option selected value="3">3 años</option>
      <option value="4">4 años</option>
      <option value="5">5 años</option> 
    </select>
  </div>
</div>

<div id="div_primary" class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Grado de Primaria</label>
  <div class="col-md-6 col-sm-12 col-xs-12">
    <select required="required" id="primary_grade" name="primary_grade" class="select2_single form-control">
      <option selected value="1">1er Grado</option>
      <option value="2">2do Grado</option>
      <option value="3">3er Grado</option> 
      <option value="4">4to Grado</option> 
      <option value="5">5to Grado</option> 
      <option value="6">6to Grado</option>      
    </select>
  </div>
</div>

<div id="div_secundary" class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Grado de Secundaria</label>
  <div class="col-md-6 col-sm-12 col-xs-12">
    <select required="required" id="secundary_grade" name="secundary_grade" class="select2_single form-control">
      <option selected value="1">1er Grado</option>
      <option value="2">2do Grado</option>
      <option value="3">3er Grado</option> 
      <option value="4">4to Grado</option> 
      <option value="5">5to Grado</option> 
    </select>
  </div>
</div>


<script>

$("#div_primary").hide();
$("#div_secundary").hide();

function change_level() {
    var level = document.getElementById("level").value;

    switch(level){
      case "1":
        $("#div_primary").hide(500);
        $("#div_secundary").hide(500);

        $("#div_initial").show(500);
        break;
      case "2":
        $("#div_initial").hide(500);
        $("#div_secundary").hide(500);

        $("#div_primary").show(500);
        break;
      case "3":
        $("#div_initial").hide(500);
        $("#div_primary").hide(500);

        $("#div_secundary").show(500);
        break;
      default:
        console.log(level);
        break;
    }
}
</script>