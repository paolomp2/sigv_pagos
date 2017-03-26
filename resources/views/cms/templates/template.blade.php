@include('cms.layouts.header')

<body class="nav-md">
	<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

  <div class="container body">


    <div class="main_container">

        @include('cms.layouts.siderbar')

        @include('cms.layouts.menu')

        @include('cms.layouts.content')       

    </div>
  </div>
  @include('cms.layouts.scripts')

  @yield('scripts')

  @yield('scripts_combobox_level_classroom')
</body>