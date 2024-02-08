<!DOCTYPE html>
<html lang="{{str_replace('_','-', app()->getLocale())}}">
<head>


    <title>Mesure du CO2 en milieux clos</title>

    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--End META -->

    <!--Vite Assets-->
    {!! Vite::asset('/js/App.jsx', ["react"])  !!}
    <!-- End Vite Assets -->



</head>
<body>
<div id="app"></div>
</body>
</html>
