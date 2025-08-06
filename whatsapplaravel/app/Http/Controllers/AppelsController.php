<?php

namespace App\Http\Controllers;

use App\Models\Appels;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;

class appelsController extends Controller
{
    /**
     * Display a listing of the resource.
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
        return view('appels.index', compact('appels'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $utilisateurs = Utilisateurs::all();
        return view('appels.create', compact('utilisateurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'type' => 'required',
                'status' => 'required',
                'dateAppel' => 'required | date',
                'utilisateurEnvoyeur_id' => 'required|exists:utilisateurs,id',
                'utilisateurReceveur_id' => 'required|exists:utilisateurs,id',
            ]
        );

        // Vérification personnalisée : envoyeur != receveur
        if ($request->utilisateurEnvoyeur_id == $request->utilisateurReceveur_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['utilisateurReceveur_id' => 'L\'envoyeur et le receveur ne peuvent pas être la même personne.']);
        }

        Appels::create($request->all());
        return redirect()->route('appels.index')->with('success', 'appel ajouter avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appel = Appels::with(['envoyeurAppel', 'receveurAppel'])->findOrFail($id);
        $utilisateurs = Utilisateurs::all();
        return view('appels.show', compact('appel', 'utilisateurs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appel = Appels::findOrFail($id);
        $utilisateurs = Utilisateurs::all();
        return view('appels.edit', compact('appel', 'utilisateurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'type' => 'required',
                'status' => 'required',
                'dateAppel' => 'required | date',
                'utilisateurEnvoyeur_id' => 'required|exists:utilisateurs,id',
                'utilisateurReceveur_id' => 'required|exists:utilisateurs,id',
            ]
        );

        // Vérification personnalisée : envoyeur != receveur
        if ($request->utilisateurEnvoyeur_id == $request->utilisateurReceveur_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['utilisateurReceveur_id' => 'L\'envoyeur et le receveur ne peuvent pas être la même personne.']);
        }

        $appel = Appels::findOrFail($id);
        $appel->update($request->all());
        return redirect()->route('appels.index')->with('success', 'appel modifier avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appel = Appels::findOrFail($id);
        $appel->delete();
        return redirect()->route('appels.index')->with('success', 'appel supprimer avec succès');
    }
}
