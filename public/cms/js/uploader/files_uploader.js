//student image
$(document).ready(function() {

$("#image").change(function(e){    
    e.preventDefault();
    var token = $("#csrf-token").val();    
    var fileSelect = document.getElementById('image');
    var files = fileSelect.files;
    console.log(token);

    for (var i = 0; i < files.length; i++) {
      var formData = new FormData();
      var file = files[i];
      var file_size = Math.round((100*file.size / 1048576))/100;
      formData.append('image', file, file.name);
      formData.append('file_name', file.name);
      formData.append('size', file_size);

      $('#image_prew_div').remove();
      $('#image_prew_msg').remove();
      $('#image_prew').append("<div id='image_prew_msg' class='controls'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='last-name'>Estado:</label><span class='btn btn-success btn-sm col-md-6 col-sm-6 col-xs-12'>Subiendo imagen, espere.</span></div>");

      $.ajax({
        url: '/student/storeImage',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        data:formData,
        contentType: false,
        processData: false
      })
      .done(function(succes) {
        document.getElementById('student_id').value = succes['id'];
        $('#image_prew_msg').remove();
        $('#image_prew').append("<div id='image_prew_div' class='controls'><label class='control-label col-md-3 col-sm-3 col-xs-12' for='last-name'></label><figure><img class='col-md-6 col-sm-4 col-xs-4 avatar-view' src='/images/student/student_"+succes['id']+"."+succes['extension']+"'></figure></div>");        
      })
        .fail(function() {
          
      });
    }
});
});