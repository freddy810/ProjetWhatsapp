<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .chatAvecAutreUtilisateur {
            border: 1px solid #ccc;
            padding: 10px;
            height: 500px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .messageReceveur, .messageEnvoyeur {
            display: flex;
            flex-direction: column;
            max-width: 70%;
            padding: 8px;
            border-radius: 10px;
            word-break: break-word;
        }
        .messageReceveur { background-color: #f1f0f0; align-self: flex-start; }
        .messageEnvoyeur { background-color: #dcf8c6; align-self: flex-end; }
        .messageImage { width: 50px; height: 50px; border-radius: 5px; margin-bottom: 5px; }
        .boutonModifierSupprimerDroite { display: flex; gap: 5px; font-size: 12px; }
        .boutonModifierSupprimerDroite a { text-decoration: none; color: #555; padding: 2px 5px; border: 1px solid #ccc; border-radius: 3px; transition: 0.2s; }
        .boutonModifierSupprimerDroite a:hover { background-color: #ddd; }

        /* Formulaires texte et fichier horizontal type chat */
        .formulaireChat {
            display: flex;
            gap: 5px;
            align-items: center;
            margin-top: 10px;
        }
        .formulaireChat input[type="text"] {
            flex: 1;
        }

        /* Header avec bouton retour */
        .headerChat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
        }
        .headerChat .infosUtilisateur {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Date message */
        .dateMessage {
            font-size: 0.75rem;
            color: #888;
            text-align: center;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
@php
    $autreUtilisateurId = $idUtilisateurConnecter == $utilisateurId1 ? $utilisateurId2 : $utilisateurId1;
    $autreUtilisateurNom = $idUtilisateurConnecter == $utilisateurId1 ? $utilisateurNom2 : $utilisateurNom1;
    $photoProfilAutre = $idUtilisateurConnecter == $utilisateurId1 
        ? $photoProfilUtilisateur2 
        : $photoProfilUtilisateur1;
    $phoneAutre = $idUtilisateurConnecter == $utilisateurId1 
        ? $phoneUtilisateur2 
        : $phoneUtilisateur1;
@endphp

<div class="listesMessagesDeUtilisateurConnecter container mt-3">
    {{-- Header conversation avec bouton retour --}}
    <div class="headerChat">
        <div class="infosUtilisateur">
            @if(strlen($photoProfilAutre) > 1)
                <img src="{{ asset('images/profils/'.$photoProfilAutre) }}" 
                     alt="Photo de profil" 
                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
            @else
                <div style="width:40px;height:40px;border-radius:50%;background:#ccc;
                            display:flex;align-items:center;justify-content:center;
                            font-weight:bold;">
                    {{ $photoProfilAutre }}
                </div>
            @endif
            <div>
                <strong>{{ $autreUtilisateurNom }}</strong><br>
                <small>{{ $phoneAutre }}</small>
            </div>
        </div>

        {{-- Bouton retour --}}
        <a href="{{ route('affichage.liste', ['id' => $idUtilisateurConnecter]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    {{-- Chat messages --}}
    <div class="chatAvecAutreUtilisateur" id="chatAvecAutreUtilisateur">
        @foreach($messages as $message)
            {{-- Date du message --}}
            <div class="dateMessage" 
                 style="text-align: {{ $message['receveur'] == $idUtilisateurConnecter ? 'left' : 'right' }}; 
                        font-size: 10px; 
                        color: #888; 
                        margin-bottom: 3px;">
               {{ \Carbon\Carbon::parse($message['dateMessage'])->format('d/m/Y H:i') }}
            </div>
        
            @if ($message['receveur'] == $idUtilisateurConnecter)
                <div class="messageReceveur">
                    @if(!empty($message['imageMessage']))
                        <img src="{{ asset('imagesMessages/'.$message['imageMessage']) }}" class="messageImage">
                    @else
                        <p class="messageTexte">{{ $message['contenu'] }}</p>
                    @endif
                </div>
            @else
                <div class="messageEnvoyeur">
                    @if(!empty($message['imageMessage']))
                        <img src="{{ asset('imagesMessages/'.$message['imageMessage']) }}" class="messageImage">
                    @else
                        <p class="messageTexte">{{ $message['contenu'] }}</p>
                    @endif
                    <div class="boutonModifierSupprimerDroite">
                        @if(empty($message['imageMessage']))
                            <a href="#" class="btnModifier"
                                data-bs-toggle="modal"
                                data-bs-target="#modifierMessageModal"
                                data-message-id="{{ $message['id'] }}"
                                data-message-contenu="{{ $message['contenu'] }}">
                                Modifier
                            </a>
                        @endif
                        <a href="#" class="btnSupprimer" 
                           data-bs-toggle="modal"
                           data-bs-target="#supprimerMessageModal"
                           data-route="{{ route('message.destroy', ['id' => $message['id']]) }}">
                           Supprimer
                        </a>
                    </div>
                </div>
            @endif
        @endforeach

    </div>

    {{-- Formulaire envoyer texte + image horizontal --}}
    <div class="formulaireChat">
        
        {{-- Nouveau formulaire image avec bouton + --}}
        <form id="formImageMessage" action="{{ route('message.enregistrement') }}" method="POST" enctype="multipart/form-data" style="display:flex; gap:5px; align-items:center;">
            @csrf
            <label for="fichierMessageInput" class="btn btn-success mb-0" style="font-size:1.2rem; padding:0.5rem 0.75rem; cursor:pointer;">
                +
            </label>
            <input type="file" id="fichierMessageInput" name="fichierMessage" style="display:none;" accept="image/*"/>
            <input type="hidden" name="typeMessage" value="fichier"/>
            <input type="hidden" name="utilisateurEnvoyeurMessage_id" value="{{ $idUtilisateurConnecter }}"/>
            <input type="hidden" name="utilisateurReceveurMessage_id" value="{{ $autreUtilisateurId }}"/>
        </form>

        <form action="{{ route('message.enregistrement') }}" method="POST" class="flex-grow-1 d-flex" style="gap:5px;">
            @csrf
            <input type="text" name="contenues" placeholder="Écrire un message..." class="form-control"/>
            <input type="hidden" name="typeMessage" value="texte"/>
            <input type="hidden" name="utilisateurEnvoyeurMessage_id" value="{{ $idUtilisateurConnecter }}"/>
            <input type="hidden" name="utilisateurReceveurMessage_id" value="{{ $autreUtilisateurId }}"/>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
</div>

{{-- Modals modifier et supprimer --}}
<div class="modal fade" id="modifierMessageModal" tabindex="-1" aria-labelledby="modifierMessageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modifierMessageForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="utilisateurEnvoyeurMessage_id" value="{{ $idUtilisateurConnecter }}"/>
                <input type="hidden" name="utilisateurReceveurMessage_id" value="{{ $autreUtilisateurId }}"/>
                <input type="hidden" name="typeMessage" value="texte"/>
                <input type="hidden" name="statusMessage" value="texte"/>
                <div class="modal-header">
                    <h5 class="modal-title" id="modifierMessageLabel">Modifier le message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <textarea name="contenues" id="modifierMessageTextarea" class="form-control" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="supprimerMessageModal" tabindex="-1" aria-labelledby="supprimerMessageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="supprimerMessageForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="supprimerMessageLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer ce message ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="submit" class="btn btn-danger">Oui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Modal modifier
    var modifierModal = document.getElementById('modifierMessageModal');
    modifierModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var messageContenu = button.getAttribute('data-message-contenu');
        var messageId = button.getAttribute('data-message-id');
        var form = modifierModal.querySelector('form');
        form.action = '/messages/' + messageId;
        modifierModal.querySelector('#modifierMessageTextarea').value = messageContenu;
    });

    // Modal supprimer
    var supprimerModal = document.getElementById('supprimerMessageModal');
    supprimerModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var route = button.getAttribute('data-route');
        var form = supprimerModal.querySelector('form');
        form.action = route;
    });

    // Soumission automatique du formulaire image
    document.getElementById('fichierMessageInput').addEventListener('change', function() {
        if(this.files.length > 0){
            document.getElementById('formImageMessage').submit();
        }
    });
</script>

</body>
</html>
