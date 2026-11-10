<?php

namespace App\Http\Controllers;

use App\Services\AlerteService;

class AlerteController extends Controller
{
    public function index(AlerteService $alertes)
    {
        return view('admin.pages.alerte.index', ['alertes' => $alertes->getAlertes()]);
    }
}
