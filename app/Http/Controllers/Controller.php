<?php

namespace App\Http\Controllers;

abstract class Controller
{
    abstract public static function middleware();
}
