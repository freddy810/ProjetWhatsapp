<?php

namespace App\Http\Controllers;

use App\Models\ContactsUtilisateurs;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;

class ContactsUtilisateursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactsUtilisateurs::with(['possedeurMessage']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                    ->orWhere('numPhone', 'like', "%$search%")
                    ->orWhereHas('possedeurMessage', function ($q2) use ($search) {
                        $q2->where('nom', 'like', "%$search%")
                            ->orWhere('prenom', 'like', "%$search%");
                    });
            });
        }

        $contactsUtilisateurs = $query->get();
        return view('contactsUtilisateurs.index', compact('contactsUtilisateurs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $utilisateurs = Utilisateurs::all();
        return view('contactsUtilisateurs.create', compact('utilisateurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nom' => 'required | max:13',
                'prenom' => 'nullable | max:13',
                'numPhone' => 'required | regex:/^[0-9]+$/ |min:10  |max:10',
                'utilisateurPossedantContact_id' => 'required|exists:utilisateurs,id',
            ]
        );

        ContactsUtilisateurs::create($request->all());
        return redirect()->route('contactsUtilisateurs.index')->with('success', 'contactsUtilisateur ajouter avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contactsUtilisateur = ContactsUtilisateurs::with(['possedeurMessage'])->findOrFail($id);
        $utilisateurs = Utilisateurs::all();
        return view('contactsUtilisateurs.show', compact('contactsUtilisateur', 'utilisateurs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contactsUtilisateur = ContactsUtilisateurs::findOrFail($id);
        $utilisateurs = Utilisateurs::all();
        return view('contactsUtilisateurs.edit', compact('contactsUtilisateur', 'utilisateurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'nom' => 'required | max:13',
                'prenom' => 'nullable | max:13',
                'numPhone' => 'required | regex:/^[0-9]+$/ |min:10  |max:10',
                'utilisateurPossedantContact_id' => 'required|exists:utilisateurs,id',
            ]
        );

        $contactsUtilisateur = ContactsUtilisateurs::findOrFail($id);
        $contactsUtilisateur->update($request->all());
        return redirect()->route('contactsUtilisateurs.index')->with('success', 'contactsUtilisateur modifier avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contactsUtilisateur = ContactsUtilisateurs::findOrFail($id);
        $contactsUtilisateur->delete();
        return redirect()->route('contactsUtilisateurs.index')->with('success', 'contactsUtilisateur supprimer avec succès');
    }
}
