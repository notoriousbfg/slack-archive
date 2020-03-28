<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function home()
    {
        session_start();

        dd($_SESSION['slack']);
    }
}