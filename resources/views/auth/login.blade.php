<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>TEDU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    
    
    <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Open+Sans'>
        {!!Html::style('login/css/style.css')!!}
        
  </head>

  <body>

    <div class="cont">
  <div class="demo">
    {!!Form::open(['route'=>'auth.store','method'=>'POST', 'class'=>'login'])!!}
        <div class="login__logo">
          <img src={!!asset("login/img/logo.png")!!} alt="" class="logo" />
        </div>
        <div class="login__logo_img">
          <img src={!!asset("login/img/logo_img.png")!!} alt="" class="logo_img" />
        </div>
        <div class="login__form">
          <div class="login__row">
            <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
              <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
            </svg>
            <input type="text" name = "username" class="login__input name" placeholder="Username"/>
          </div>
          <div class="login__row">
            <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
              <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
            </svg>
            <input type="password" name = "password" class="login__input pass" placeholder="Password"/>
          </div>
          <button type="submit" class="login__submit">Ingresar</button>
          <p class="login__signup">No tienes cuenta? &nbsp;<a>Regístrate</a></p>
        </div>
    </form>
    <div class="app">
      <div class="app__top">
        <div class="app__menu-btn">
          <span></span>
        </div>
        <svg class="app__icon search svg-icon" viewBox="0 0 20 20">
          <!-- yeap, its purely hardcoded numbers straight from the head :D (same for svg above) -->
          <path d="M20,20 15.36,15.36 a9,9 0 0,1 -12.72,-12.72 a 9,9 0 0,1 12.72,12.72" />
        </svg>
        <p class="app__hello">Cargando.....</p>
        <div class="app__user">
          <img src="img/img1.jpg" alt="" class="app__user-photo" />
        </div>
        <div class="app__month">
          <span class="app__month-btn left"></span>
          <p class="app__month-name">March</p>
          <span class="app__month-btn right"></span>
        </div>
      </div>      
      <div class="app__logout">
        <svg class="app__logout-icon svg-icon" viewBox="0 0 20 20">
          <path d="M6,3 a8,8 0 1,0 8,0 M10,0 10,12"/>
        </svg>
      </div>
    </div>
  </div>
</div>
    {!!Html::script('http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js')!!}
    {!!Html::script('login/js/index.js')!!}
    <script src=''></script>
    
    
  </body>
</html>
