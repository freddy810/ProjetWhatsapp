<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

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
        // Validation de base
        $request->validate([
            'typeMessage' => 'required|in:texte,fichier',
            'utilisateurEnvoyeurMessage_id' => 'required|exists:utilisateurs,id',
            'utilisateurReceveurMessage_id' => 'required|exists:utilisateurs,id',

            // On verifie si c'est type "text" ou "fichier"
            'contenues' => $request->typeMessage === 'texte' ? 'required|string' : 'nullable',
            'fichierMessage' => $request->typeMessage === 'fichier' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|' : 'nullable'
        ]);

        // on verifie si l'envoyeur est different de receveur
        if ($request->utilisateurEnvoyeurMessage_id == $request->utilisateurReceveurMessage_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['utilisateurReceveurMessage_id' => 'L\'envoyeur et le receveur ne peuvent pas être la même personne.']);
        }

        $data = [
            'typeMessage' => $request->typeMessage,
            'statusMessage' => 'non_lus',
            'dateMessage' => Carbon::now(),
            'utilisateurEnvoyeurMessage_id' => $request->utilisateurEnvoyeurMessage_id,
            'utilisateurReceveurMessage_id' => $request->utilisateurReceveurMessage_id,
        ];

        if ($request->typeMessage === 'texte') {
            $data['contenues'] = $request->contenues;
            $data['fichierMessage'] = null;
        } else {
            $fileName = time() . '_' . $request->file('fichierMessage')->getClientOriginalName();
            $request->file('fichierMessage')->move(public_path('imagesMessages'), $fileName);

            $data['fichierMessage'] = $fileName;
            $data['contenues'] = null;
        }

        Messages::create($data);

        return redirect()->route('messages.index')->with('success', 'Message ajouté avec succès');
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
        // Validation dynamique
        $request->validate([
            'typeMessage' => 'required|in:texte,fichier',
            'statusMessage' => 'required',
            'utilisateurEnvoyeurMessage_id' => 'required|exists:utilisateurs,id',
            'utilisateurReceveurMessage_id' => 'required|exists:utilisateurs,id',
            'contenues' => $request->typeMessage === 'texte' ? 'required|string' : 'nullable',
            'fichierMessage' => $request->typeMessage === 'fichier' && $request->hasFile('fichierMessage')
                ? 'required|image|mimes:jpeg,png,jpg,gif,svg|'
                : 'nullable'
        ]);

        // Vérification : envoyeur ≠ receveur
        if ($request->utilisateurEnvoyeurMessage_id == $request->utilisateurReceveurMessage_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['utilisateurReceveurMessage_id' => 'L\'envoyeur et le receveur ne peuvent pas être la même personne.']);
        }

        $message = Messages::findOrFail($id);

        $data = [
            'typeMessage' => $request->typeMessage,
            'statusMessage' => $request->statusMessage,
            'utilisateurEnvoyeurMessage_id' => $request->utilisateurEnvoyeurMessage_id,
            'utilisateurReceveurMessage_id' => $request->utilisateurReceveurMessage_id,
        ];

        if ($request->typeMessage === 'texte') {
            $data['contenues'] = $request->contenues;
            $data['fichierMessage'] = $message->fichierMessage; // on garde l'ancien fichier s'il existait
        } else {
            // Si un nouveau fichier est uploadé
            if ($request->hasFile('fichierMessage')) {
                $fileName = time() . '_' . $request->file('fichierMessage')->getClientOriginalName();
                $request->file('fichierMessage')->move(public_path('imagesMessages'), $fileName);

                $data['fichierMessage'] = $fileName;
            } else {
                // Si aucun nouveau fichier, on garde l'ancien
                $data['fichierMessage'] = $message->fichierMessage;
            }
            $data['contenues'] = null;
        }

        $message->update($data);

        return redirect()->route('messages.index')->with('success', 'Message modifié avec succès');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $message = Messages::findOrFail($id);

        // Si le message contient un fichier, on le supprime du dossier public/imagesMessages
        if ($message->typeMessage === 'fichier' && $message->fichierMessage) {
            $filePath = public_path('imagesMessages/' . $message->fichierMessage);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        // Supprimer l'enregistrement en BDD
        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message supprimé avec succès');
    }
}
