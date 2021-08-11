<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class ServiceController extends Controller
{
    function getSitemap() {
        $sitemap = App::make('sitemap');

        $sitemap->add(url('/'), Carbon::now());
        $sitemap->add(url('/login'), Carbon::now());
        $sitemap->add(url('/register'), Carbon::now());

        return $sitemap->render('xml');
    }
}
