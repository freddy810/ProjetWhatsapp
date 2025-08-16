<?php

namespace App\Http\Controllers\utile;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\Utilisateurs;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UtileMessageController extends Controller
{
    /**
     * Recupere les ids des messages d'un utilisateur (en relation avec la fonction "login" dans "UtilisateursController" grace à 
     * la route lié)
     */
    public function getMessagesIdsByUtilisateur($utilisateurId)
    {
        $utilisateur = Utilisateurs::find($utilisateurId);
        if (!$utilisateur) {
            return redirect()->route('login.form')->withErrors(['error' => 'Utilisateur non trouvé.']);
        }

        // Récupérer tous les messages où l'utilisateur est soit envoyeur soit receveur, avec les relations nécessaires
        $messages = Messages::with(['envoyeurMessage', 'receveurMessage'])
            ->where('utilisateurEnvoyeurMessage_id', $utilisateurId)
            ->orWhere('utilisateurReceveurMessage_id', $utilisateurId)
            ->orderBy('dateMessage', 'desc') // ordre décroissant pour avoir les plus récents en premier
            ->get();

        $conversations = [];

        foreach ($messages as $message) {
            // Clé unique pour la conversation, ordre invariant
            $ids = [
                $message->utilisateurEnvoyeurMessage_id,
                $message->utilisateurReceveurMessage_id,
            ];
            sort($ids); // trie pour que la plus petite soit en premier
            $conversationKey = implode('_', $ids);

            // On garde uniquement le premier message rencontré par clé (le plus récent grâce à orderBy)
            if (!isset($conversations[$conversationKey])) {
                $conversations[$conversationKey] = $message;
            }
        }

        // Maintenant on prépare le tableau à afficher
        $resultats = collect($conversations)->map(function ($message) use ($utilisateurId) {

            // Détermination du contenu du message avec la nouvelle condition
            if (!empty($message->fichierMessage)) {
                // Si l'utilisateur connecté est l'envoyeur
                if ($message->utilisateurEnvoyeurMessage_id == $utilisateurId) {
                    $contenu = 'Vous avez envoyé une image';
                } else {
                    $contenu = 'Vous avez reçu une image';
                }
            } else {
                // Cas normal, sans fichier image
                if ($message->utilisateurEnvoyeurMessage_id == $utilisateurId) {
                    $contenu = 'vous: ' . $message->contenues;
                } else {
                    $contenu = $message->contenues;
                }
            }

            // Préparation du tableau de retour
            if ($message->utilisateurEnvoyeurMessage_id == $utilisateurId) {
                return [
                    'contenu' => $contenu,
                    'nomUtilisateurChat' => $message->receveurMessage->nom,
                    'imageUtilisateurChat' => !empty($message->receveurMessage->photoProfil)
                        ? $message->receveurMessage->photoProfil
                        : substr($message->receveurMessage->nom, 0, 1),
                    'idEnvoyeur' => $utilisateurId,
                    'idReceveur' => $message->receveurMessage->id,
                    'statusMessage' => "aucun",
                ];
            } else {
                return [
                    'contenu' => $contenu,
                    'nomUtilisateurChat' => $message->envoyeurMessage->nom,
                    'imageUtilisateurChat' => !empty($message->envoyeurMessage->photoProfil)
                        ? $message->envoyeurMessage->photoProfil
                        : substr($message->envoyeurMessage->nom, 0, 1),
                    'idEnvoyeur' => $message->envoyeurMessage->id,
                    'idReceveur' => $utilisateurId,
                    'statusMessage' => $message->statusMessage,
                ];
            }
        });

        return view('utilisation.affichage', compact('utilisateur', 'resultats'));
    }


    /**
     * Récupère les messages échangés entre deux utilisateurs avec leurs ids et les ids des envoyeurs/receveurs.
     */
    public function getMessagesDetailsBetweenUsers($utilisateur, $utilisateurId1, $utilisateurId2,)
    {
        // Récupérer les utilisateurs (nom + id)
        $user1 = Utilisateurs::find($utilisateurId1);
        $user2 = Utilisateurs::find($utilisateurId2);

        $messages = Messages::where(function ($query) use ($utilisateurId1, $utilisateurId2) {
            $query->where('utilisateurEnvoyeurMessage_id', $utilisateurId1)
                ->where('utilisateurReceveurMessage_id', $utilisateurId2);
        })
            ->orWhere(function ($query) use ($utilisateurId1, $utilisateurId2) {
                $query->where('utilisateurEnvoyeurMessage_id', $utilisateurId2)
                    ->where('utilisateurReceveurMessage_id', $utilisateurId1);
            })
            ->orderBy('dateMessage', 'asc')
            ->get(['id', 'utilisateurEnvoyeurMessage_id', 'utilisateurReceveurMessage_id', 'contenues', 'fichierMessage', 'dateMessage'])
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'imageMessage' => $message->fichierMessage,
                    'contenu' => $message->contenues,
                    'envoyeur' => $message->utilisateurEnvoyeurMessage_id,
                    'receveur' => $message->utilisateurReceveurMessage_id,
                    'dateMessage' => $message->dateMessage
                ];
            });

        return view('utilisation.message', [
            'messages' => $messages,
            'idUtilisateurConnecter' => $utilisateur,
            'utilisateurId1' => $utilisateurId1,
            'utilisateurId2' => $utilisateurId2,
            'utilisateurNom1' => $user1->nom,
            'utilisateurNom2' =>  $user2->nom,
            'phoneUtilisateur1' => $user1->telephone,
            'phoneUtilisateur2' => $user2->telephone,
            'photoProfilUtilisateur1' => !empty($user1->photoProfil) ? $user1->photoProfil : substr($user1->nom, 0, 1),
            'photoProfilUtilisateur2' => !empty($user2->photoProfil) ? $user2->photoProfil : substr($user2->nom, 0, 1),
        ]);
    }

    /**
     * Creation de nouveau message
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

        return redirect()->back();
    }

    /**
     * Modification de message.
     */
    public function update(Request $request, $id)
    {
        // Validation dynamique
        $request->validate([
            'utilisateurEnvoyeurMessage_id' => 'required|exists:utilisateurs,id',
            'utilisateurReceveurMessage_id' => 'required|exists:utilisateurs,id',
            'typeMessage' => 'required',
            'statusMessage' => 'required',
            'contenues' => 'required',
        ]);

        $message = Messages::findOrFail($id);

        $data = [
            'statusMessage' => $request->statusMessage,
            'typeMessage' => $request->typeMessage,
            'contenues' => $request->contenues,
            'utilisateurEnvoyeurMessage_id' => $request->utilisateurEnvoyeurMessage_id,
            'utilisateurReceveurMessage_id' => $request->utilisateurReceveurMessage_id,
        ];

        $message->update($data);
        return redirect()->back();
    }

    /**
     * suppression de message
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

        return redirect()->back();
    }


    /**
     * nouveau message avec nouveau utilisateur
     */
    public function nouvelleDiscussionAvecUtilisateur(Request $request)
    {
        $telephone = $request->input('telephone');
        $contenu = $request->input('contenu');
        $idConnecte = $request->input('idConnecte');

        // Chercher l'utilisateur connecté
        $utilisateurConnecte = Utilisateurs::find($idConnecte);
        if (!$utilisateurConnecte) {
            return redirect()->back()->withErrors(['non_connecte' => 'Utilisateur connecté introuvable.']);
        }

        // Chercher l'utilisateur par téléphone
        $receveur = Utilisateurs::where('telephone', $telephone)->first();
        if (!$receveur) {
            return redirect()->back()->withErrors(['telephone' => 'Aucun utilisateur trouvé avec ce numéro.']);
        }

        // Vérifier que l'utilisateur trouvé n'est pas le même que l'utilisateur connecté
        if ($receveur->id == $utilisateurConnecte->id) {
            return redirect()->back()->withErrors(['telephone' => 'Vous ne pouvez pas créer une discussion avec vous-même.']);
        }

        // Vérifier si un message existe déjà entre les deux utilisateurs (dans les deux sens)
        $messageExistant = Messages::where(function ($q) use ($utilisateurConnecte, $receveur) {
            $q->where('utilisateurEnvoyeurMessage_id', $utilisateurConnecte->id)
                ->where('utilisateurReceveurMessage_id', $receveur->id);
        })->orWhere(function ($q) use ($utilisateurConnecte, $receveur) {
            $q->where('utilisateurEnvoyeurMessage_id', $receveur->id)
                ->where('utilisateurReceveurMessage_id', $utilisateurConnecte->id);
        })->first();

        if ($messageExistant) {
            return redirect()->back()->with('info', 'Une discussion existe déjà avec cet utilisateur.');
        }

        // Préparer la requête pour la fonction store
        $storeRequest = $request->merge([
            'typeMessage' => $request->hasFile('fichierMessage') ? 'fichier' : 'texte',
            'utilisateurEnvoyeurMessage_id' => $utilisateurConnecte->id,
            'utilisateurReceveurMessage_id' => $receveur->id,
            'contenues' => $contenu,
        ]);

        // Appeler la fonction store pour créer le message
        return $this->store($storeRequest);
    }
}
