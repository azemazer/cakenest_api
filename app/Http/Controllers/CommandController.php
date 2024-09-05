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
            return response("User not connected", 401);
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
            return response("User not connected", 401);
        }
        // Validate
        $validated = $request->validate([
            'cupcakes' => 'required|array',
        ]); // Structure de l'array: array d'objet type { cupcake: x, quantity: x }

        $stock_error = [];
        $new_stocks = collect([]);
        $pivot_props = [];

        // On vérifie les stocks
        foreach($validated['cupcakes'] as $cupcake){
            $commanded_stock = $cupcake['quantity'];
            $cupcake_item = Cupcake::find($cupcake['cupcake']);
            $available_stock = $cupcake_item->quantity;
            if ($available_stock < $commanded_stock){
                array_push($stock_error, $cupcake_item->title);
            } else {
                $cupcake_item->quantity -=  $commanded_stock;
            }
            $new_stocks->push($cupcake_item);
            array_push($pivot_props, [
                $cupcake_item->id => [
                    "quantity" => $cupcake_item->quantity,
                    "price_at_time" => $cupcake_item->price
                ]
            ]);
        }

        if (sizeof($stock_error) > 0){
            return response([
                "message" => "Erreur: certains cupcakes ne sont pas disponible.",
                "data" => $stock_error
            ], 400);
        }

        $sum = $new_stocks->sum('price');
        // On modifie les stocks si il n'y a pas d'erreur de stock
        $new_stocks->each(function($item) {
            $item->save();
        });


        // On créée la commande
        $command = new Command([
            "total" => $sum,
            "user_id" => $user->id
        ]);

        /*
[
    cupcake_id => ['quantity' =>, $quantity 'price_at_time' => $expires],
    2 => ['expires' => $expires],
]
        */
        $command->attach($new_stocks->pluck('id'), $pivot_props);
        $command->save();

        return response($command);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user){
            return response("User not connected", 401);
        }
        $command = Command::with('cupcakes')
        ->findOrFail($id);
        return response($command);
    }

    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'command_id' => 'required|integer'
        ]);
        $command = Command::find($validated["command_id"]);
        $command->status = Command::$status_list['canceled'];
        //$command->status = Command::CANCELED;

        $command->save();

        return response($command);
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'command_id' => 'required|integer'
        ]);
        $command = Command::find($validated["command_id"]);
        $command->status = Command::$status_list['paid'];

        $command->save();

        // Lancer quelconque procédure de paiement

        return response($command);
    }
}
