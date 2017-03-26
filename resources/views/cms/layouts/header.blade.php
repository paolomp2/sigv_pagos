<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>SIGC</title>
  <!-- MATERIALIZE -->

  <!--Import Google Icon Font-->
  {!!Html::style('http://fonts.googleapis.com/icon?family=Material+Icons')!!}
  <!--Import materialize.css-->
  <!--{!!Html::style('materialize/css/materialize.css')!!}-->
  {!!Html::style('materialize/css/custom.css')!!}

  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- Bootstrap core CSS -->

  {!!Html::style('cms/css/bootstrap.min.css')!!}
  {!!Html::style('cms/fonts/css/font-awesome.min.css')!!}
  {!!Html::style('cms/css/animate.min.css')!!}

  <!-- Custom styling plus plugins -->
  {!!Html::style('cms/css/custom.css')!!}
  {!!Html::style('cms/css/icheck/flat/green.css')!!}

  <!-- Custom styling plus plugins -->
  {!!Html::style('cms/css/maps/jquery-jvectormap-2.0.3.css')!!}
  {!!Html::style('cms/css/floatexamples.css')!!}

  {!!Html::script('cms/js/jquery.min.js')!!}
  {!!Html::script('cms/js/nprogress.js')!!}

  @if($gc->form)
  {!!Html::script('cms/js/parsley.min.js')!!}
  {!!Html::script('cms/js/uploader/files_uploader.js')!!}
  @endif
  
  <!-- Breadcrumb -->
  

  @if($gc->select)
  {!!Html::style('cms/css/select/select2.min.css')!!}
  @endif

  {!!Html::style('cms/css/icheck/flat/green.css')!!}
  
  <!--[if lt IE 9]>
    {!!Html::script('cms/js/ie8-responsive-file-warning.js')!!}
  <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    {!!Html::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')!!}
    {!!Html::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js')!!}
  <![endif]-->

</head>





