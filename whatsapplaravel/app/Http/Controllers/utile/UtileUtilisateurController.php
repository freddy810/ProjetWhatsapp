<?php

namespace App\Http\Controllers\utile;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;

class UtileUtilisateurController extends Controller
{
    public function loginForm()
    {
        return view('auth.login'); // créer cette vue login.blade.php
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|max:13',
            'prenom' => 'nullable|max:13',
            'dateNaissance' => 'required|date',
            'telephone' => 'required|regex:/^[0-9]+$/|min:10|max:10',
            'photoProfil' => 'nullable',
            'motDePasse' => 'required|min:4',
        ]);

        $donnee = $request->all();

        // Définit le status par défaut à "hors_ligne"
        $donnee['status'] = 'hors_ligne';
        $donnee['type'] = 'simple_utilisateur';

        // Upload de la photo si présente
        if ($request->hasFile('photoProfil')) {
            $fichier = $request->file('photoProfil');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $fichier->move(public_path('images/profils'), $nomFichier);
            $donnee['photoProfil'] = $nomFichier;
        }

        Utilisateurs::create($donnee);

        return redirect()->back()->with('success', 'Utilisateur créé avec succès.');
    }

    // Traitement du login
    public function login(Request $request)
    {
        $request->validate([
            'telephone' => 'required',
            'motDePasse' => 'required'
        ]);

        $utilisateur = Utilisateurs::where('telephone', $request->telephone)
            ->where('motDePasse', $request->motDePasse)
            ->first();

        if ($utilisateur) {
            session(['utilisateur' => $utilisateur]); // Stocker l'utilisateur en session
            return redirect()->route('affichage.liste', ['id' => $utilisateur->id]);
        }

        return back()->withErrors(['telephone' => 'Identifiants incorrects']);
    }


    // Déconnexion
    public function logout()
    {
        session()->forget('utilisateur');
        return redirect()->route('login.form')->with('success', 'Déconnecté avec succès');
    }


    // Traitement du changement de mot de passe
    public function changerMotDePasse(Request $request)
    {
        $request->validate([
            'telephone' => 'required',
            'nouveauMotDePasse' => 'required|min:4',
            'confirmationMotDePasse' => 'required|min:4',
        ]);

        // Chercher l'utilisateur par téléphone
        $user = Utilisateurs::where('telephone', $request->telephone)->first();

        if (!$user) {
            return back()->withErrors(['telephone' => 'Aucun utilisateur trouvé avec ce numéro de téléphone.']);
        }

        // Vérifier que le nouveau mot de passe et la confirmation sont identiques
        if ($request->nouveauMotDePasse !== $request->confirmationMotDePasse) {
            return back()->withErrors(['confirmationMotDePasse' => 'Le mot de passe et la confirmation ne correspondent pas.']);
        }

        // Mise à jour du mot de passe
        $user->motDePasse = $request->nouveauMotDePasse;
        $user->save();

        return redirect()->back()->with('success_password', 'Mot de passe changé avec succès.');
    }


    //Modificaiton d'un utilisateur
    public function update(Request $request, Utilisateurs $utilisateur)
    {
        $request->validate([
            'nom' => 'required|max:13',
            'prenom' => 'nullable|max:13',
            'dateNaissance' => 'required|date',
            'telephone' => 'required|regex:/^[0-9]+$/|min:10|max:10',
            'photoProfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:99999',
        ]);

        // 🔍 Vérification si le téléphone existe déjà pour un autre utilisateur
        $telephoneExiste = Utilisateurs::where('telephone', $request->telephone)
            ->where('id', '!=', $utilisateur->id)
            ->exists();

        if ($telephoneExiste) {
            return redirect()->back()
                ->with('error', 'Le numéro de téléphone est déjà utilisé par un autre compte.')
                ->withInput();
        }

        $donnee = $request->except(['supprimerPhoto']);

        // Suppression de la photo si demandé
        if ($request->supprimerPhoto == '1') {
            if ($utilisateur->photoProfil && file_exists(public_path('images/profils/' . $utilisateur->photoProfil))) {
                unlink(public_path('images/profils/' . $utilisateur->photoProfil));
            }
            $donnee['photoProfil'] = null; // 🔹 Forcer à null
        }

        // Upload nouvelle photo
        if ($request->hasFile('photoProfil')) {
            if ($utilisateur->photoProfil && file_exists(public_path('images/profils/' . $utilisateur->photoProfil))) {
                unlink(public_path('images/profils/' . $utilisateur->photoProfil));
            }
            $fichier = $request->file('photoProfil');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $fichier->move(public_path('images/profils'), $nomFichier);
            $donnee['photoProfil'] = $nomFichier;
        }

        $utilisateur->update($donnee);

        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }

    //vue qui redirige vers l'affichage de tous les utilisateurs
    public function afficherListeUtilisateurs($idUtilisateurConnecte)
    {
        $utilisateurs = $this->getTousUtilisateursExcept($idUtilisateurConnecte);

        return view('utilisation.listeUtilisateurs', compact('utilisateurs', 'idUtilisateurConnecte'));
    }


    //recuperer tous les utilisateurs à part celui qui est connecté
    public function getTousUtilisateursExcept($idUtilisateurConnecte)
    {
        // Récupérer tous les utilisateurs sauf celui connecté
        $utilisateurs = Utilisateurs::where('id', '!=', $idUtilisateurConnecte)->get();

        return $utilisateurs;
    }

    //Pour la creation de nouvelle utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|max:13',
            'type' => 'required',
            'prenom' => 'nullable|max:13',
            'dateNaissance' => 'required|date',
            'telephone' => 'required|regex:/^[0-9]+$/|min:10|max:10',
            'photoProfil' => 'nullable',
            'motDePasse' => 'required|min:4',
        ]);

        $donnee = $request->all();

        // Définit le status par défaut à "hors_ligne"
        $donnee['status'] = 'hors_ligne';

        // Upload de la photo si présente
        if ($request->hasFile('photoProfil')) {
            $fichier = $request->file('photoProfil');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $fichier->move(public_path('images/profils'), $nomFichier);
            $donnee['photoProfil'] = $nomFichier;
        }

        Utilisateurs::create($donnee);

        return redirect()->route('affichage.listeAdmin', ['id' => session('utilisateur')->id])
            ->with('success', 'Utilisateur créé avec succès.');
    }

    // Suppression utilisateur
    public function destroy(Utilisateurs $utilisateur)
    {
        // Vérifier si l'utilisateur a une photo et si elle existe physiquement
        if ($utilisateur->photoProfil && file_exists(public_path('images/profils/' . $utilisateur->photoProfil))) {
            unlink(public_path('images/profils/' . $utilisateur->photoProfil));
        }

        // Supprimer l'utilisateur
        $utilisateur->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
