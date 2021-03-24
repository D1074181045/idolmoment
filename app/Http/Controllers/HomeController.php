<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\GameInfo;
use App\Models\CoolDown;
use App\Models\OwnCharacter;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function spa()
    {
        return view('home.app');
    }
}
