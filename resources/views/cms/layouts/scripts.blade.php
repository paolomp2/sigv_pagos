  <!--Import jQuery before materialize.js-->
  {!! HTML::script('materialize/js/materialize.min.js') !!}

  {!! HTML::script('cms/js/jquery.min.js') !!}
  {!! HTML::script('cms/js/bootstrap.min.js') !!}
  {!! HTML::script('cms/js/custom.js') !!}

  <!-- Breadcrumbs -->
  {!! HTML::script('cms/js/modernizr.js') !!} <!-- Modernizr -->

  @if($gc->table)
  {!!Html::style('cms/css/jquery.dataTables.min.css')!!}
  {!!Html::script('cms/js/data_tables/jquery.dataTables.js')!!}
  <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
      $('#list_table').DataTable();
    } );
  </script>
  @endif

  @if($gc->form)
  {!!Html::script('cms/js/input_mask/jquery.inputmask.js')!!}
  @endif

  @if($gc->select)
  {!!Html::script('cms/js/select/select2.full.js')!!}
  @endif

  @if($gc->date)
  <!-- daterangepicker -->
  {!!Html::script('cms/js/moment/moment.min.js')!!}
  {!!Html::script('cms/js/datepicker/daterangepicker.js')!!}
  @endif

  @if($gc->picture)
  <!-- image cropping -->
  {!!Html::script('cms/js/cropping/cropper.min.js')!!}
  {!!Html::script('cms/js/cropping/main.js')!!}
  @endif

  {!!Html::script('cms/js/icheck/icheck.min.js')!!}

  <!-- Confirm modal -->
  {!!Html::script('cms/js/confirm-bootstrap.js')!!}
  
  <script type="text/javascript">    
  $(document).ready(function () {
    console.log("okkkkk")
      $('#submit').click(function(){
           /* when the submit button in the modal is clicked, submit the form */
          alert('submitting');
          $('#formfield').submit();
      });
  });
  </script>

  