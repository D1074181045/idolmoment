<!DOCTYPE html>
<html lang="zh-tw">
<head>
    @include('global.head')
    @include('user.layouts.style')
</head>
<body>
<div id="app">
    @include('user.layouts.header')
    <loading ref="loading"></loading>
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

