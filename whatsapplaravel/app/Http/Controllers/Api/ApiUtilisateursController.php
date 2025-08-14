<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiUtilisateursController extends Controller
{
    /**
     * Liste des utilisateurs avec recherche optionnelle.
     */
    public function index(Request $request)
    {
        $query = Utilisateurs::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%")
                    ->orWhere('dateNaissance', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
            });
        }

        $utilisateurs = $query->get();

        return response()->json([
            'success' => true,
            'data' => $utilisateurs,
        ]);
    }


    /**
     * Stocke un nouvel utilisateur avec upload image possible.
     */
    public function store(Request $request)
    {
        // Valider les champs sauf 'type' et 'status' car on les fixe nous-mêmes
        $request->validate([
            'nom' => 'required|max:13',
            'prenom' => 'nullable|max:13',
            'dateNaissance' => 'required|date',
            'telephone' => 'required|regex:/^[0-9]+$/|size:10',
            'photoProfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:99999',
            'motDePasse' => 'required|string|min:4',
            'confirmeMotDePasse' => 'required|string|min:4',
        ]);

        // Vérification que motDePasse et confirmeMotDePasse correspondent
        if ($request->motDePasse !== $request->confirmeMotDePasse) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe et sa confirmation ne correspondent pas.',
            ], 422);
        }

        $data = $request->all();

        // Fixer les valeurs par défaut
        $data['status'] = 'hors_ligne';
        $data['type'] = 'simple_utilisateur';

        // On ne stocke que motDePasse, pas confirmeMotDePasse
        $data['motDePasse'] = $request->motDePasse;
        unset($data['confirmeMotDePasse']);

        if ($request->hasFile('photoProfil')) {
            $file = $request->file('photoProfil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/profils'), $filename);
            $data['photoProfil'] = $filename;
        }

        $utilisateur = Utilisateurs::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur ajouté avec succès',
            'data' => $utilisateur,
        ], 201);
    }



    /**
     * Affiche un utilisateur.
     */
    public function show($id)
    {
        $utilisateur = Utilisateurs::find($id);

        if (!$utilisateur) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $utilisateur,
        ]);
    }

    /**
     * Met à jour un utilisateur avec upload image possible.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|max:13',
            'type' => 'required|string',
            'prenom' => 'nullable|max:13',
            'dateNaissance' => 'required|date',
            'telephone' => 'required|regex:/^[0-9]+$/|size:10',
            'photoProfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:99999',
            'status' => 'required|string',
        ]);

        $utilisateur = Utilisateurs::find($id);

        if (!$utilisateur) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        $data = $request->all();

        if ($request->hasFile('photoProfil')) {
            // Supprimer ancienne image si existante
            if ($utilisateur->photoProfil && file_exists(public_path('images/profils/' . $utilisateur->photoProfil))) {
                unlink(public_path('images/profils/' . $utilisateur->photoProfil));
            }

            $file = $request->file('photoProfil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/profils'), $filename);
            $data['photoProfil'] = $filename;
        }

        $utilisateur->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur modifié avec succès',
            'data' => $utilisateur,
        ]);
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy($id)
    {
        $utilisateur = Utilisateurs::find($id);

        if (!$utilisateur) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        // Supprimer image profil si existante
        if ($utilisateur->photoProfil && file_exists(public_path('images/profils/' . $utilisateur->photoProfil))) {
            unlink(public_path('images/profils/' . $utilisateur->photoProfil));
        }

        $utilisateur->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }

    // --- ------------------------Authentification simple en JSON---------------------------------------- ---

    /**
     * Login API (envoie téléphone + motDePasse en JSON).
     */
    public function login(Request $request)
    {
        $request->validate([
            'telephone' => 'required',
            'motDePasse' => 'required',
        ]);

        $utilisateur = Utilisateurs::where('telephone', $request->telephone)
            ->where('motDePasse', $request->motDePasse)
            ->first();

        if ($utilisateur) {
            // Mettre à jour le status à "en_ligne"
            $utilisateur->status = 'en_ligne';
            $utilisateur->save();

            return response()->json([
                'success' => true,
                'message' => 'Connecté avec succès',
                'data' => $utilisateur,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Identifiants incorrects',
        ], 401);
    }


    /**
     * Déconnexion : côté API, généralement on supprime le token côté client.
     * Ici, juste réponse JSON.
     */
    public function logout(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:utilisateurs,id'
        ]);

        $utilisateur = Utilisateurs::find($request->id);

        // Mettre le statut à "hors_ligne"
        $utilisateur->status = 'hors_ligne';
        $utilisateur->save();

        return response()->json([
            'success' => true,
            'message' => 'Déconnecté avec succès',
            'data' => $utilisateur
        ]);
    }


    /**
     * Changement de mot de passe via API JSON avec téléphone.
     */
    public function changerMotDePasse(Request $request)
    {
        $request->validate([
            'telephone' => 'required|exists:utilisateurs,telephone',
            'nouveau' => 'required|min:4|confirmed', // Le champ 'confirmation' doit être 'nouveau_confirmation'
        ], [
            'nouveau.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Récupérer l'utilisateur via son téléphone
        $utilisateur = Utilisateurs::where('telephone', $request->telephone)->first();

        // Mettre à jour le mot de passe
        $utilisateur->motDePasse = $request->nouveau;
        $utilisateur->save();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe changé avec succès.',
        ]);
    }

    /**
     * Vérifie le type d'un utilisateur (simple_utilisateur ou administrateur).
     */
    public function verifierTypeUtilisateur($id)
    {
        $utilisateur = Utilisateurs::find($id);

        if (!$utilisateur) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        if ($utilisateur->type === "simple_utilisateur") {
            return response()->json([
                'success' => true,
                'type' => 'simple_utilisateur',
                'message' => 'Cet utilisateur est un simple utilisateur.'
            ]);
        } elseif ($utilisateur->type === "administrateur") {
            return response()->json([
                'success' => true,
                'type' => 'administrateur',
                'message' => 'Cet utilisateur est un administrateur.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'type' => $utilisateur->type,
                'message' => 'Type d’utilisateur non reconnu.'
            ]);
        }
    }
}
