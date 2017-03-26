<!-- page content -->
@include('cms.layouts.confirmModal')
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        @include('cms.layouts.breadcrumb')
      </div>      
    </div>
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h3>{!!$gc->page_name!!} </h3>
            @if(!$gc->form)
              <ul class="button_right">
                @if($gc->default_buttons)                
                  @if($gc->trash)
                  <li>
                  	<a href="/{!!$gc->url_base!!}/">
                  		<button type="button" class="btn btn-warning btn-lg text_over">
                  			<i class="fa fa-mail-reply"></i>
                  		</button>
                  		
                  	</a>
                  	<spam class="text_over_span">{!!$gc->button_new or "Retornar a la lista"!!}</spam>
                  </li>
                  @else
                  <li>
                  	<a href="/{!!$gc->url_base!!}/create" type="button" class="btn btn-info btn-lg text_over">
                  		<i class="fa fa-file"></i>
                  		
                  	</a>
                  	<spam class="text_over_span">{!!$gc->button_new or "Insertar nuevo elemento"!!}</spam>
                  </li>
                  <li>
                  	<a href="/{!!$gc->url_base!!}/trash/trash" >
                  		<button type="button" class="btn btn-warning btn-lg text_over">
                  			<i class="fa fa-trash-o"></i>
                  		</button>
                  		
                  	</a>
                  	<spam class="text_over_span">{!!$gc->button_new or "Ir a la papelera"!!}</spam>
                  </li>
                  @endif                
                @endif
              
                @if($gc->add_buttons)
                  <li>
                    <a href="/{!!$gc->url_base!!}/{!!$gc->entity_to_edit->id_md5!!}/add_elements">
                      <button type="button" class="btn btn-info btn-lg">
                        <i class="fa fa-file"></i>
                      </button>                    
                    </a>
                    <spam class="text_over_span">{!!$gc->button_new or "Insertar nuevo elemento"!!}</spam>
                  </li>

                  <li>
                    <a href="/{!!$gc->url_base!!}/">
                      <button type="button" class="btn btn-warning btn-lg">
                        <i class="fa fa-mail-reply"></i>
                      </button>                    
                    </a>
                    <spam class="text_over_span">{!!$gc->button_new or "Retornar a la lista"!!}</spam>
                  </li>
                @endif
              </ul>
            @endif
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

			@yield('content')

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->