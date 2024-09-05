<?php

namespace App\Http\Controllers;

use App\Models\Command;
use App\Models\Cupcake;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user){
            return response("User not connected", 501);
        }
        $commands = $user->commands;
        return response($commands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user){
            return response("User not connected", 501);
        }
        // Validate
        $validated = $request->validate([
            'cupcakes' => 'required|array',
        ]); // Structure de l'array: array d'objet type { cupcake: x, quantity: x }

        $stock_error = [];
        $new_stocks = collect([]);

        // On vérifie les stocks
        foreach($validated['cupcakes'] as $cupcake){
            $cupcake_item = Cupcake::find($cupcake['cupcake']);
            if ($cupcake_item->quantity < $cupcake['quantity']){
                array_push($stock_error, $cupcake_item->title);
            } else {
                $cupcake_item->quantity -= $cupcake['quantity'];
            }
            $new_stocks->push($cupcake_item);
        }

        if (sizeof($stock_error) > 0){
            return response([
                "message" => "Erreur: certains cupcakes ne sont pas disponible.",
                "data" => $stock_error
            ]);
        }

        // On modifie les stocks si il n'y a pas d'erreur de stock
        $new_stocks->each(function($item) {
            $item->save();
        });

        $sum = 0;

        // On créée la commande
        $command = new Command([
            "total" => $sum
        ]);
        $command->save();

        return response($command);
    }

    /**
     * Display the specified resource.
     */
    public function show(Command $command)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Command $command)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Command $command)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Command $command)
    {
        //
    }
}
