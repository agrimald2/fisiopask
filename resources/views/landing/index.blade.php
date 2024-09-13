<!DOCTYPE html>
<html class="no-js" lang="es">
<head>
    @include('landing.head')
</head>
<body>
    <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T4ZXR9N');</script>
    <!-- End Google Tag Manager 
    @include('landing.loader')
    -->
    @include('landing.header')
    
        <div class="icon-bar">
            <a href="https://www.facebook.com/fisiosaludperu/" class="facebook"><i class="fa fa-facebook"></i></a> 
            <a href="https://wa.me/51987327809" class="twitter"><i class="fa fa-whatsapp"></i></a> 
            <a href="https://fisiosalud.pe/cita" class="google i_text">Reservar <br/> Cita</a> 
            <a href="tel:+51955089005" class="linkedin i_text">Llámanos <br/> Ahora</a>
        </div>
        <div class="st-content">
            @include('landing.home')
            @include('landing.features')
            @include('landing.about')
            @include('landing.treatments')
            @include('landing.therapies')
           
            @include('landing.gallery')
            @include('landing.doctors')
            @include('landing.testimonies')
            @include('landing.stats')
            
            @include('landing.questions')
            @include('landing.agendar')

            @include('landing.offices')
            @include('landing.map')
        </div>
        @include('landing.footer')
        @include('landing.video')
</body>
<style>
.icon-bar {
  position: fixed;
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  right: 0;
  z-index:200;
}

.icon-bar a {
  display: block;
  text-align: center;
  padding: 16px;
  transition: all 0.3s ease;
  color: white;
  font-size: 35px;
}

.icon-bar a:hover {
  background-color: #000;
}

.facebook {
  background: #3B5998;
  color: white;
}

.twitter {
  background: #25D366;
  color: white;
}

.google {
  background: #dd4b39;
  color: white;
}

.linkedin {
  background: #007bb5;
  color: white;
}

.youtube {
  background: #bb0000;
  color: white;
}

.content {
  margin-left: 75px;
  font-size: 30px;
}
.i_text{
	font-size: 15px!important;
    font-family: arial;
}
</style>
</html>
