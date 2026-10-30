<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(): View
    {
        $promos = Promo::query()
            ->active()
            ->orderBy('ends_at')
            ->paginate(9);

        return view('customer.promo.index', compact('promos'));
    }

    public function show(int $id): View
    {
        $promo = Promo::query()
            ->active()
            ->findOrFail($id);

        return view('customer.promo.show', compact('promo'));
    }
}