<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Command;
use App\Models\Cupcake;
use App\Models\Promocode;
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
            'promocode' => 'nullable|string'
        ]); // Structure de l'array: array d'objet type { cupcake: x, quantity: x }

        $stock_error = [];
        $new_stocks = collect([]);
        $pivot_props = [];
        $sum = 0;

        // On vérifie les stocks
        foreach($validated['cupcakes'] as $cupcake){
            $commanded_stock = $cupcake['quantity'];
            $cupcake_item = Cupcake::find($cupcake['cupcake']);
            $available_stock = $cupcake_item->quantity;
            $sum += $commanded_stock * $cupcake_item->price;

            // Formatage des props table pivot
            array_push($pivot_props, [
                    "quantity" => $cupcake_item->quantity,
                    "price_at_time" => $cupcake_item->price
            ]);
            if ($available_stock < $commanded_stock){
                array_push($stock_error, $cupcake_item->title);
            } else {
                $cupcake_item->quantity -=  $commanded_stock;
            }
            $new_stocks->push($cupcake_item);

        }

        if (sizeof($stock_error) > 0){
            return response([
                "message" => "Erreur: certains cupcakes ne sont pas disponible.",
                "data" => $stock_error
            ], 400);
        }
        // On modifie les stocks si il n'y a pas d'erreur de stock
        $new_stocks->each(function($item) {
            $item->save();
        });

        $total_reductions = 0;
        if($validated["promocode"]){
            $promocode = Promocode::where('code', $validated["promocode"])
            ->first();
            if(!$promocode){
                return response('Promocode doesnt exist.', 403);
            }
            if(new DateTime('now') > new DateTime($promocode->validity_date)){
                return response("Promo code expired.", 403);
            }
            $total_reductions = $sum - ($sum * $promocode->percentage / 100);
        }
        // On créée la commande
        $command = new Command([
            "total" => $sum,
            "user_id" => $user->id,
            "total_reductions" => (int) $total_reductions
        ]);
        $command->save();

        $formatted_stocks = $new_stocks->mapWithKeys(function($value, $key) use ($pivot_props) {
            return [$value->id => $pivot_props[$key]];
        });
        $command->cupcakes()->attach($formatted_stocks);
        $command->save();
        $command->load("cupcakes");

        return response()->json($command, 201);
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

        /*
        Reste code pour remettre les stocks
        */

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
