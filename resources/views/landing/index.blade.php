<!DOCTYPE html>
<html class="no-js" lang="es">
<head>
    @include('landing.head')
</head>
<body>
    
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TF6PTM7"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @include('landing.loader')
    
    @include('landing.header')
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
</html>

