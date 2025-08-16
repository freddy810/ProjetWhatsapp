<?php

namespace App\Http\Controllers;

use App\Models\Utilisateurs;
use Illuminate\Http\Request;

class UtilisateursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Utilisateurs::query();

        //pour la recherche
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
        return view('utilisateurs.index', compact('utilisateurs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('utilisateurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nom' => 'required | max:13',
                'type' => 'required',
                'prenom' => 'nullable | max:13',
                'dateNaissance' => 'required | date',
                'telephone' => 'required | regex:/^[0-9]+$/ |min:10  |max:10',
                'photoProfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:99999',
                'status' => 'required',

            ]
        );

        $donnee = $request->all();

        //--------Pour l'image------------------------------------------
        if ($request->hasFile('photoProfil')) {
            $fichier = $request->file('photoProfil');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $fichier->move(public_path('images/profils'), $nomFichier); // Stocké dans public/images/profils
            $donnee['photoProfil'] = $nomFichier;
        }

        Utilisateurs::create($donnee);

        return redirect()->route('utilisateurs.index')->with('success', 'utilisateur ajouter avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Utilisateurs $utilisateur)
    {
        return view('utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Utilisateurs $utilisateur)
    {
        return view('utilisateurs.edit', compact('utilisateur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Utilisateurs $utilisateur)
    {
        $request->validate(
            [
                'nom' => 'required | max:13',
                'type' => 'required',
                'prenom' => 'nullable | max:13',
                'dateNaissance' => 'required | date',
                'telephone' => 'required | regex:/^[0-9]+$/ |min:10  |max:10',
                'photoProfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:99999',
                'status' => 'required',

            ]
        );

        $donnee = $request->all();

        //--------Pour l'image------------------------------------------
        if ($request->hasFile('photoProfil')) {
            // Supprimer l'ancienne image si elle existe
            if ($utilisateur->photoProfil && file_exists(public_path('images/profils/' . $utilisateur->photoProfil))) {
                unlink(public_path('images/profils/' . $utilisateur->photoProfil));
            }

            $fichier = $request->file('photoProfil');
            $nomFichier = time() . '_' . $fichier->getClientOriginalName();
            $fichier->move(public_path('images/profils'), $nomFichier);
            $donnee['photoProfil'] = $nomFichier;
        }

        $utilisateur->update($donnee);

        return redirect()->route('utilisateurs.index')->with('success', 'utilisateur modifier avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utilisateurs $utilisateur)
    {
        $utilisateur->delete();
        return redirect()->route('utilisateurs.index')->with('success', 'utilisateur supprimer avec succès');
    }
}
