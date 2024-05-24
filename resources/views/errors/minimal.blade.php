<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <style>
            h1,h2{color:#000;text-align:center}a,img{margin:0 auto}.central,a,body{display:flex}body{width:100vw;height:100vh;overflow:hidden;background-color:#edfff9}.central{margin:auto;flex-direction:column}img{min-width:100px;max-width:400px;width:15vw;height:auto}h1{font-family:'Baloo 2';font-weight:800;font-size:35px}a,h2{font-family:'Baloo 2';font-weight:700}h2{font-size:30px}a{height:fit-content;width:fit-content;padding:5px 25px;background-color:#25d382;border-radius:15px;border:none;color:#fff;font-size:20px;cursor:pointer;flex-direction:row;flex-wrap:nowrap;transition:.4s cubic-bezier(.28, -.46, .33, 1.5)}a:hover{-webkit-transform:scale(1.05);background-color:#8edf87}
        </style>
    </head>
    <body>
        <div class="central">
            <img src="/assets/icons/logo/Logo.svg">
            <h1> @yield('message') </h1>
            <h2> @yield('code') </h2>
            <a href="/"> accueil </a>
        </div>
    </body>
</html>
