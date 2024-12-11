<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use DateTime;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    function getPromocodeByCode(Request $request){
        $validated = $request->validate([
            'code' => 'required|string|max:255'
        ]);
        $promocode = Promocode::where('code', $validated['code'])
        ->firstOrFail();
        if(new DateTime('now') > new DateTime($promocode->validity_date)){
            return response("Promo code expired.", 403);
        }
        return response($promocode);
    }
}
