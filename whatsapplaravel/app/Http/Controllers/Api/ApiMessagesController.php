<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;

class ApiMessagesController extends Controller
{
    /**
     * Liste des messages avec recherche optionnelle.
     */
    public function index(Request $request)
    {
        $query = Messages::with(['envoyeurMessage', 'receveurMessage']);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('contenues', 'like', "%$search%")
                    ->orWhere('typeMessage', 'like', "%$search%")
                    ->orWhere('statusMessage', 'like', "%$search%")
                    ->orWhere('dateMessage', 'like', "%$search%")
                    ->orWhereHas('envoyeurMessage', function ($q2) use ($search) {
                        $q2->where('nom', 'like', "%$search%")
                            ->orWhere('prenom', 'like', "%$search%");
                    })
                    ->orWhereHas('receveurMessage', function ($q3) use ($search) {
                        $q3->where('nom', 'like', "%$search%")
                            ->orWhere('prenom', 'like', "%$search%");
                    });
            });
        }

        $messages = $query->get();

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    /**
     * Stocke un nouveau message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contenues' => 'required|string',
            'typeMessage' => 'required|string',
            'utilisateurEnvoyeurMessage_id' => 'required|exists:utilisateurs,id',
            'utilisateurReceveurMessage_id' => 'required|exists:utilisateurs,id',
        ]);

        if ($request->utilisateurEnvoyeurMessage_id == $request->utilisateurReceveurMessage_id) {
            return response()->json([
                'success' => false,
                'message' => "L'envoyeur et le receveur ne peuvent pas être la même personne."
            ], 422);
        }

        $message = Messages::create([
            'contenues' => $request->contenues,
            'typeMessage' => $request->typeMessage,
            'statusMessage' => 'non_lus',
            'dateMessage' => now(),
            'utilisateurEnvoyeurMessage_id' => $request->utilisateurEnvoyeurMessage_id,
            'utilisateurReceveurMessage_id' => $request->utilisateurReceveurMessage_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message ajouté avec succès',
            'data' => $message,
        ], 201);
    }


    /**
     * Affiche un message spécifique.
     */
    public function show($id)
    {
        $message = Messages::with(['envoyeurMessage', 'receveurMessage'])->find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $message,
        ]);
    }

    /**
     * Met à jour un message.
     */
    public function update($id)
    {
        $message = Messages::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message non trouvé',
            ], 404);
        }

        $message->update([
            'statusMessage' => 'lus',
            'dateMessage' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme lu avec succès',
            'data' => $message,
        ]);
    }


    /**
     * Supprime un message.
     */
    public function destroy($id)
    {
        $message = Messages::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message non trouvé',
            ], 404);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message supprimé avec succès',
        ]);
    }

    //-----------------------------------------Spécifique-----------------------------------------------------------------------------
    /**
     * Récupérer le dernier message entre deux utilisateurs.
     */
    public function dernierMessageEntreDeuxUtilisateurs($utilisateur1_id, $utilisateur2_id)
    {
        $dernierMessage = Messages::where(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
            $query->where('utilisateurEnvoyeurMessage_id', $utilisateur1_id)
                ->where('utilisateurReceveurMessage_id', $utilisateur2_id);
        })
            ->orWhere(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
                $query->where('utilisateurEnvoyeurMessage_id', $utilisateur2_id)
                    ->where('utilisateurReceveurMessage_id', $utilisateur1_id);
            })
            ->orderBy('dateMessage', 'desc')
            ->first(['id']);  // Ne récupérer que l'id

        if (!$dernierMessage) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun message trouvé entre ces utilisateurs.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'dernier_message_id' => $dernierMessage->id,
        ]);
    }


    /**
     * Vérifier si le dernier message entre deux utilisateurs est "lus" ou "non_lus".
     */
    public function statutDernierMessageEntreDeuxUtilisateurs($utilisateur1_id, $utilisateur2_id)
    {
        $dernierMessage = Messages::where(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
            $query->where('utilisateurEnvoyeurMessage_id', $utilisateur1_id)
                ->where('utilisateurReceveurMessage_id', $utilisateur2_id);
        })
            ->orWhere(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
                $query->where('utilisateurEnvoyeurMessage_id', $utilisateur2_id)
                    ->where('utilisateurReceveurMessage_id', $utilisateur1_id);
            })
            ->orderBy('dateMessage', 'desc')
            ->first();

        if (!$dernierMessage) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun message trouvé entre ces utilisateurs.',
            ], 404);
        }

        // Vérification du statut
        $statut = ($dernierMessage->statusMessage === 'lus') ? 'lus' : 'non_lus';

        return response()->json([
            'success' => true,
            'statut' => $statut,
        ]);
    }


    /**
     * Récupérer l'ID de l'envoyeur du dernier message entre deux utilisateurs.
     */
    public function idEnvoyeurDernierMessage($utilisateur1_id, $utilisateur2_id)
    {
        $dernierMessage = Messages::where(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
            $query->where('utilisateurEnvoyeurMessage_id', $utilisateur1_id)
                ->where('utilisateurReceveurMessage_id', $utilisateur2_id);
        })
            ->orWhere(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
                $query->where('utilisateurEnvoyeurMessage_id', $utilisateur2_id)
                    ->where('utilisateurReceveurMessage_id', $utilisateur1_id);
            })
            ->orderBy('dateMessage', 'desc')
            ->first();

        if (!$dernierMessage) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun message trouvé entre ces utilisateurs.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'envoyeur_id' => $dernierMessage->utilisateurEnvoyeurMessage_id,
        ]);
    }

    /**
     * Récupérer l'ID du receveur du dernier message entre deux utilisateurs.
     */
    public function idReceveurDernierMessage($utilisateur1_id, $utilisateur2_id)
    {
        $dernierMessage = Messages::where(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
            $query->where('utilisateurEnvoyeurMessage_id', $utilisateur1_id)
                ->where('utilisateurReceveurMessage_id', $utilisateur2_id);
        })
            ->orWhere(function ($query) use ($utilisateur1_id, $utilisateur2_id) {
                $query->where('utilisateurEnvoyeurMessage_id', $utilisateur2_id)
                    ->where('utilisateurReceveurMessage_id', $utilisateur1_id);
            })
            ->orderBy('dateMessage', 'desc')
            ->first();

        if (!$dernierMessage) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun message trouvé entre ces utilisateurs.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'receveur_id' => $dernierMessage->utilisateurReceveurMessage_id,
        ]);
    }

    /**
     * Marquer tous les messages reçus d'un utilisateur comme lus.
     */
    public function marquerMessagesCommeLus($receveur_id, $envoyeur_id)
    {
        // Récupérer les messages non lus envoyés par $envoyeur_id à $receveur_id
        $messages = Messages::where('utilisateurReceveurMessage_id', $receveur_id)
            ->where('utilisateurEnvoyeurMessage_id', $envoyeur_id)
            ->where('statusMessage', '!=', 'lus')
            ->update(['statusMessage' => 'lus']);

        return response()->json([
            'success' => true,
            'message' => "Messages marqués comme lus avec succès.",
            'nombre_messages_modifies' => $messages
        ]);
    }

    /**
     * Compter le nombre de messages non lus pour un utilisateur.
     */
    public function compterMessagesNonLus($utilisateur_id)
    {
        $count = Messages::where('utilisateurReceveurMessage_id', $utilisateur_id)
            ->where('statusMessage', 'non_lus')
            ->count();

        return response()->json([
            'success' => true,
            'nombre_non_lus' => $count
        ]);
    }
}
