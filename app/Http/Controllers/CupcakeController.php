<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cupcake;

class CupcakeController extends Controller
{
    function getCupcake(Request $request, $id){
        $cupcake = Cupcake::findOrFail($id);
        return response($cupcake);
    }

    function getCupcakes(){
        $cupcakes = Cupcake::all();
        return response($cupcakes);
    }

    function updateCupcake(Request $request, $id){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'isAvailable' => 'required|boolean',
            'isAdvertised' => 'required|boolean',
        ]);
        $cupcake = Cupcake::find($id);
        $cupcake->price = $validated['price'];
        $cupcake->title = $validated['title'];
        $cupcake->quantity = $validated['quantity'];
        $cupcake->isAvailable = $validated['isAvailable'];
        $cupcake->isAdvertised = $validated['isAdvertised'];
        $cupcake->save();
        return response($cupcake);
    }

    function createCupcake(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|integer',
            'quantity' => 'nullable|integer',
            'isAvailable' => 'nullable|boolean',
            'isAdvertised' => 'nullable|boolean',
        ]);

        $cupcake = Cupcake::create($validated);
        return response($cupcake);
    }

    function deleteCupcake(Request $request, $id){
        $cupcake = Cupcake::find($id);
        $cupcake->delete();

        return response($cupcake);
    }
}
