<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InformationsController extends Controller
{
  public function tos(): View
  {
    return view('informations.tos');
  }

  public function privacy(): View
  {
    return view('informations.privacy');
  }
}
