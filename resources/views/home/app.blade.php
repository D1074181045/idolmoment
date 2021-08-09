<!doctype html>

<?php

use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

try {
    $self_name = Auth::user()->name;
} catch (Exception $e) {
    $self_name = "";
}

?>

<html lang="zh-tw">
<head>
    @include('global.head')
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
