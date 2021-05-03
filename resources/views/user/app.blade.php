<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes, shrink-to-fit=no"/>
    <link rel="icon" href="{{ asset('favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet"/>
    @include('user.layouts.style')
</head>
<body>
<div id="app">
    @include('user.layouts.header')
    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
@include('user.layouts.script')
</body>
</html>

