<!doctype html>

<?php

use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

$self_name = Auth::user()->name;
$self_name_encrypt = Controller::UserNameEncrypt2($self_name);

?>

<html lang="zh-tw">
<head>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes, shrink-to-fit=no"/>
    <link rel="icon" href="{{ asset('favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet"/>
    @include('home.layouts.style')
</head>
<body>
<div id="app">
    @include('home.layouts.header')
    <loading ref="loading"></loading>
    <div class="col-md-6 center" v-if="this.loaded">
        <keep-alive>
            <router-view v-if="$route.meta.KeepAlive" :key="$route.path"></router-view>
        </keep-alive>
        <router-view v-if="!$route.meta.KeepAlive"></router-view>
    </div>
</div>
@include('footer')
@include('home.layouts.script')
</body>
</html>
