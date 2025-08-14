<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appels;
use Illuminate\Http\Request;

class ApiAppelsController extends Controller
{
    /**
     * Retourne la liste des appels (JSON), avec filtre optionnel search.
     */
    public function index(Request $request)
    {
        $query = Appels::with(['envoyeurAppel', 'receveurAppel']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('dateAppel', 'like', "%$search%")
                    ->orWhereHas('envoyeurAppel', function ($q2) use ($search) {
                        $q2->where('nom', 'like', "%$search%")
                            ->orWhere('prenom', 'like', "%$search%");
                    })
                    ->orWhereHas('receveurAppel', function ($q2) use ($search) {
                        $q2->where('nom', 'like', "%$search%")
                            ->orWhere('prenom', 'like', "%$search%");
                    });
            });
        }

        $appels = $query->get();

        return response()->json([
            'success' => true,
            'data' => $appels
        ]);
    }

    /**
     * Pas besoin de méthode create car formulaire est côté React.
     */

    /**
     * Stocke un nouvel appel reçu en JSON depuis React.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'status' => 'required|string',
            'dateAppel' => 'required|date',
            'utilisateurEnvoyeur_id' => 'required|exists:utilisateurs,id',
            'utilisateurReceveur_id' => 'required|exists:utilisateurs,id',
        ]);

        if ($request->utilisateurEnvoyeur_id == $request->utilisateurReceveur_id) {
            return response()->json([
                'success' => false,
                'message' => "L'envoyeur et le receveur ne peuvent pas être la même personne."
            ], 422);
        }

        $appel = Appels::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Appel ajouté avec succès',
            'data' => $appel
        ], 201);
    }

    /**
     * Affiche un appel spécifique (JSON).
     */
    public function show($id)
    {
        $appel = Appels::with(['envoyeurAppel', 'receveurAppel'])->find($id);

        if (!$appel) {
            return response()->json([
                'success' => false,
                'message' => 'Appel non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $appel
        ]);
    }

    /**
     * Pas besoin de méthode edit car formulaire est côté React.
     */

    /**
     * Met à jour un appel existant via JSON.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'status' => 'required|string',
            'dateAppel' => 'required|date',
            'utilisateurEnvoyeur_id' => 'required|exists:utilisateurs,id',
            'utilisateurReceveur_id' => 'required|exists:utilisateurs,id',
        ]);

        if ($request->utilisateurEnvoyeur_id == $request->utilisateurReceveur_id) {
            return response()->json([
                'success' => false,
                'message' => "L'envoyeur et le receveur ne peuvent pas être la même personne."
            ], 422);
        }

        $appel = Appels::find($id);

        if (!$appel) {
            return response()->json([
                'success' => false,
                'message' => 'Appel non trouvé'
            ], 404);
        }

        $appel->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Appel modifié avec succès',
            'data' => $appel
        ]);
    }

    /**
     * Supprime un appel.
     */
    public function destroy($id)
    {
        $appel = Appels::find($id);

        if (!$appel) {
            return response()->json([
                'success' => false,
                'message' => 'Appel non trouvé'
            ], 404);
        }

        $appel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appel supprimé avec succès'
        ]);
    }
}
