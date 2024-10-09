<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Aggiungi un elemento ai preferiti
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|string',
            'type' => 'required|in:movie,tv',
        ]);

        $favorite = Favorite::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'type' => $request->type,
        ]);

        return response()->json(['message' => 'Aggiunto ai preferiti con successo!', 'favorite' => $favorite], 201);
    }

    // Mostra i preferiti dell'utente
    public function index()
    {
        $favorites = Auth::user()->favorites;

        return response()->json($favorites);
    }

    // Rimuovi un elemento dai preferiti
    public function destroy($id)
    {
        $favorite = Favorite::where('user_id', Auth::id())->where('id', $id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Rimosso dai preferiti con successo!']);
        } else {
            return response()->json(['error' => 'Elemento non trovato'], 404);
        }
    }
}
