<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if(!isset($_COOKIE['pagehistory'])){
            setcookie('pagehistory',$_SERVER['REQUEST_URI'].'|');
        }
        else {
            $_COOKIE['pagehistory'] .= $_SERVER['REQUEST_URI'].'|';
        }

    }

}
