<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Utilisateurs;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Messages::with(['envoyeurMessage', 'receveurMessage']);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('contenues', 'like', "%$search%")
                    ->orWhere('typeMessage', 'like', "%$search%")
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
        return view('messages.index', compact('messages'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $utilisateurs = Utilisateurs::all();
        return view('messages.create', compact('utilisateurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'contenues' => 'required',
                'typeMessage' => 'required',
                'dateMessage' => 'required | date',
                'utilisateurEnvoyeurMessage_id' => 'required|exists:utilisateurs,id',
                'utilisateurReceveurMessage_id' => 'required|exists:utilisateurs,id',
            ]
        );

        // Vérification personnalisée : envoyeur != receveur
        if ($request->utilisateurEnvoyeurMessage_id == $request->utilisateurReceveurMessage_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['utilisateurReceveurMessage_id' => 'L\'envoyeur et le receveur ne peuvent pas être la même personne.']);
        }

        Messages::create($request->all());
        return redirect()->route('messages.index')->with('success', 'message ajouter avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $utilisateurs = Utilisateurs::all();
        $message = Messages::with(['envoyeurMessage', 'receveurMessage'])->findOrFail($id);
        return view('messages.show', compact('message', 'utilisateurs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $message = Messages::findOrFail($id);
        $utilisateurs = Utilisateurs::all();
        return view('messages.edit', compact('message', 'utilisateurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'contenues' => 'required',
                'typeMessage' => 'required',
                'dateMessage' => 'required | date',
                'utilisateurEnvoyeurMessage_id' => 'required|exists:utilisateurs,id',
                'utilisateurReceveurMessage_id' => 'required|exists:utilisateurs,id',
            ]
        );

        // Vérification personnalisée : envoyeur != receveur
        if ($request->utilisateurEnvoyeurMessage_id == $request->utilisateurReceveurMessage_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['utilisateurReceveurMessage_id' => 'L\'envoyeur et le receveur ne peuvent pas être la même personne.']);
        }


        $message = Messages::findOrFail($id);
        $message->update($request->all());
        return redirect()->route('messages.index')->with('success', 'message modifier avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $message = Messages::findOrFail($id);
        $message->delete();
        return redirect()->route('messages.index')->with('success', 'message supprimer avec succès');
    }
}
