<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\UserActivities;
use App\Models\Visits;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(){
        // dd(123);
        return view('pages.Dashboard.index');
    }
}
