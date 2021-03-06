<div class="col-md-3 left_col">
  <div class="left_col scroll-view">

    <div class="navbar nav_title" style="border: 0;">
      <img src={!!asset("login/img/logo.png")!!} alt="" class="login__logo" />
      <img src={!!asset("login/img/logo_img.png")!!} alt="" class="logo" />
    </div>
    <div class="clearfix"></div>

    <!-- menu prile quick info -->
    <div class="profile">
      <div class="profile_pic">
        <img src={!!asset("cms/images/count.png")!!} alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Bienvenido,</span>
        <h2>{{$gc->username}}</h2>
      </div>
    </div>
    <!-- /menu prile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <p>
      
      <div class="menu_section">
        <h3>&nbsp</h3>
        
        <h3>Pagos</h3>
        <ul class="nav side-menu">
          @if($gc->id_rol==1)
          <li><a><i class="fa fa-pencil-square-o"></i> Conceptos de Pagos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/concepts_groups/">Grupos de Conceptos</a></li>
              <li><a href="/concepts">Conceptos</a></li>
            </ul>
          </li>
          
          <li><a><i class="fa fa-retweet"></i> Dstos e Intereses<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/discounts/">Descuentos</a></li>
              <li><a href="/interests">Intereses</a></li>
            </ul>
          </li>
          @endif
          <li><a><i class="fa fa-money"></i> Realizar pagos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/showListStudents/">Realizar pagos</a></li>
              <li><a href="/createPaymentDocument/">Ingreso manual de boletas</a></li>
              <li><a href="/Payments/void/selectDocument">Anular Boleta</a></li>
            </ul>
          </li>
        </ul>
      </div>
      
      
      <div class="menu_section">
        <h3>Infraestructura</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-users"></i> Aulas y Grupos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/classrooms/">Aulas</a></li>
              <li><a href="/groups/">Grupos</a></li>
            </ul>
          </li>      
          
          <!--<li><a><i class="fa fa-users"></i> Grupos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              
            </ul>
          </li>--> 
        </ul>
      </div>
      
      <div class="menu_section">
        <h3>Alumnos y Familiares</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-male"></i> Alumnos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/students/">Lista de Alumnos</a></li>
              <!-- <li><a href="/students/all/all">Todos los alumnos</a></li>
              <li><a href="/students/bulcked/bulcked/">Alumnos por volcado</a></li> -->
              <li><a href="/students/enrolling_fast/enrolling_fast/">Matrícula rápida</a></li>
              <li><a href="/family_members/">Lista de Familiares</a></li>
              <li><a href="/students/bulck/bulck">Importar desde Excel</a></li>
            </ul>
          </li>

          <!--<li><a><i class="fa fa-upload"></i> Matrícula<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">              
            </ul>
          </li>
          <li><a><i class="fa fa-sitemap"></i> Familiar<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">              
            </ul>
          </li>                       
          <li><a><i class="fa fa-upload"></i>Cargar desde<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              
            </ul>
          </li>-->
        </ul>
      </div>
      <div class="menu_section">
        <h3>Reportes</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-area-chart"></i> Pagos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/reports/consolidatedDebtReportGet">Consolidado por aula</a></li>
              <li><a href="/reports/paymentsByDatesReportGet">Pagos por fechas</a></li>
              <!--<li><a href="/reports/debtorReportGet">Top de deudores</a></li>-->
            </ul>
          </li>      
          
          <!--<li><a><i class="fa fa-users"></i> Grupos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              
            </ul>
          </li>--> 
        </ul>
      </div>
      @if($gc->id_rol==1)
      <div class="menu_section">
        <h3>TESTS</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-area-chart"></i> Pagos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              <li><a href="/Tests/CreatePaymentDocument/BasicDates_01">Crear Documentos</a></li>
              <!--<li><a href="/reports/debtorReportGet">Top de deudores</a></li>-->
            </ul>
          </li>      
          
          <!--<li><a><i class="fa fa-users"></i> Grupos<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu" style="display: none">
              
            </ul>
          </li>--> 
        </ul>
      </div>
      @endif
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">            
      <a data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>