<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'cupcakes' => 'required|array',
        ]);
        // Structure de l'array: array d'objet type { cupcake: x, quantity: x }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        // On supprime tout
        $cart->cupcakes()->detach();

        //Puis on réattache tout les nouveaux éléments
        foreach($validated['cupcakes'] as $cupcake){
            $cart->cupcakes()->attach($cupcake['cupcake'], [
                'quantity' => $cupcake['quantity']
            ]);
        }
        $cart->save();
        return response($cart);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $cart = $request->user()->cart()
        ->with('cupcakes')
        ->first();
        return response($cart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
