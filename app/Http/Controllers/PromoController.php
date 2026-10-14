<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        return view('customer.promo.index');
    }

    public function show($id)
    {
        return view('customer.promo.show', compact('id'));
    }
}
