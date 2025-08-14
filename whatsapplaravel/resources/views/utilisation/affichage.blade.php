<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Ventes')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            position: relative;
        }
        .sidebar {
            width: 350px;
            max-width: 100%;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .sidebar-header .user-info-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            color: #fff;
            object-fit: cover;
        }
        .search-box {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
        }
        .search-box input {
            width: 100%;
            padding: 5px 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }
        .user-list {
            max-height: 500px;
            overflow-y: auto;
        }
        .user-list li {
            list-style: none;
            border-bottom: 1px solid #eee;
        }
        .user-list li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }
        .user-list li a:hover {
            background-color: #f1f1f1;
        }
        .user-list img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-info {
            flex: 1;
        }
        .user-info strong {
            display: block;
            font-size: 1rem;
        }
        .user-info .last-message {
            font-size: 0.875rem;
            color: #555;
        }
        .user-info .last-message.unread {
            font-weight: bold;
            color: #000;
        }
        /* Bouton Nouvelle discussion à l'extérieur */
        .btn-nouvelle-discussion {
            font-size: 1.5rem;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            cursor: pointer;
            position: fixed;
            bottom: 30px;
            right: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        .btn-nouvelle-discussion:hover {
            background-color: #0b5ed7;
        }
        /* Modal plein écran pour liste des utilisateurs */
        .modal-fullscreen {
            max-width: 100%;
            width: 100%;
            margin: 0;
        }
        .modal-body-full {
            max-height: 80vh;
            overflow-y: auto;
        }
        /* Style pour la photo de profil dans le modal */
        #photoContainer {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            margin: 0 auto 15px auto;
        }
        #photoContainer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #removePhoto {
    position: absolute;
    top: 5px;  /* légèrement à l’intérieur du rond */
    right: 15px; /* légèrement à l’intérieur du rond */
    background: red;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
}
    </style>
</head>
<body>

<div class="sidebar">
    <!-- Header utilisateur connecté avec avatar et déconnexion -->
    <div class="sidebar-header">
        <div class="user-info-header">
            @if(!empty($utilisateur->photoProfil))
                <img src="{{ asset('images/profils/' . $utilisateur->photoProfil) }}" alt="Profil" class="user-avatar">
            @else
                <div class="user-avatar">{{ strtoupper(substr($utilisateur->nom, 0, 1)) }}</div>
            @endif
            <strong>{{ $utilisateur->nom }}</strong>
        </div>

        <div class="header-actions d-flex gap-2">
            <!-- Bouton Liste des utilisateurs (Admin seulement) -->
            @if($utilisateur->type === "administrateur")
                <a href="{{ route('affichage.listeAdmin', ['id' => session('utilisateur')->id]) }}" class="btn btn-info">
                    👁 Voir tous les utilisateurs
                </a>
            @endif

            <!-- Bouton Modifier les informations -->
            <button type="button" class="btn btn-sm btn-warning" title="Modifier mes infos"
                data-bs-toggle="modal" data-bs-target="#modifierInfosModal">
                <i class="bi bi-pencil-square"></i>
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Déconnecter</button>
            </form>
        </div>
    </div>

    <!-- Optionnel : barre de recherche -->
    <div class="search-box">
        <input type="text" placeholder="Rechercher un contact...">
    </div>

    <!-- Liste des utilisateurs -->
    <ul class="user-list m-0 p-0">
        @foreach($resultats as $resultat)
            <li>
                <a href="{{ route('message.message', ['idConnecter'=> $utilisateur->id,'id1' => $resultat['idEnvoyeur'], 'id2' => $resultat['idReceveur']]) }}">
                    @if (mb_strlen($resultat['imageUtilisateurChat']) > 1)
                        <img src="{{ asset('images/profils/' . $resultat['imageUtilisateurChat']) }}" alt="Profil">
                    @else
                        <div class="user-avatar">{{ strtoupper($resultat['imageUtilisateurChat']) }}</div>
                    @endif
                    <div class="user-info">
                        <strong>{{ $resultat['nomUtilisateurChat'] }}</strong>
                        <span class="last-message {{ $resultat['statusMessage'] == 'non_lus' ? 'unread' : '' }}">
                            {{ $resultat['contenu'] }}
                        </span>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<!-- Affichage de message d'erreur pour la modification des informations de l'utilisateur connécté -->
@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<!-- Modal Modifier les informations utilisateur connécté -->
<div class="modal fade" id="modifierInfosModal" tabindex="-1" aria-labelledby="modifierInfosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('utilisateur.update', $utilisateur->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="modifierInfosLabel">Modifier mes informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Zone photo profil -->
                <div class="mb-3 position-relative">
                    <label class="form-label d-block">Photo de profil</label>
                    <div id="photoContainer">
                        @if(!empty($utilisateur->photoProfil))
                            <img id="photoPreview" src="{{ asset('images/profils/' . $utilisateur->photoProfil) }}">
                        @else
                            <img id="photoPreview" src="" style="display:none;">
                        @endif
                        <span id="photoText" @if(!empty($utilisateur->photoProfil)) style="display:none;" @endif>Ajouter une image</span>
                        <span id="removePhoto" @if(empty($utilisateur->photoProfil)) style="display:none;" @endif>&times;</span>
                    </div>
                    
                    <input type="file" id="photoProfil" name="photoProfil" accept="image/*" style="display:none;">
                    <input type="hidden" name="supprimerPhoto" id="supprimerPhoto" value="0">
                </div>

                <!-- Autres champs -->
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ $utilisateur->nom }}" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ $utilisateur->prenom }}">
                </div>
                <div class="mb-3">
                    <label for="dateNaissance" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" value="{{ $utilisateur->dateNaissance }}" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $utilisateur->telephone }}" required>
                </div>
                <div class="mb-3">
                    <label for="motDePasse" class="form-label">Mot de passe</label>
                    <input type="text" class="form-control" id="motDePasse" name="motDePasse" value="{{ $utilisateur->motDePasse }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Bouton Nouvelle Discussion -->
<button type="button" class="btn-nouvelle-discussion" 
        title="Nouvelle discussion"
        data-bs-toggle="modal" 
        data-bs-target="#nouvelleDiscussionModal">
    <i class="bi bi-plus"></i>
</button>

{{-- Affichage des messages d'erreur ou de info pour la nouvelle discussion --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
@endif

<!-- Modal Nouvelle Discussion -->
<div class="modal fade" id="nouvelleDiscussionModal" tabindex="-1" aria-labelledby="nouvelleDiscussionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('nouvelle.discussion') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="nouvelleDiscussionLabel">Nouvelle discussion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="telephone" class="form-label">Numéro de téléphone</label>
                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Entrer le numéro...">
                </div>
                <div class="mb-3 d-flex align-items-center gap-2">
                    <textarea class="form-control" id="message" name="contenu" rows="3" placeholder="Écrire un message..."></textarea>
                    <label class="btn btn-secondary mb-0">
                        <i class="bi bi-plus"></i> Image
                        <input type="file" id="fichierMessage" name="fichierMessage" accept="image/*" hidden onchange="this.form.submit()">
                    </label>
                </div>
                <input type="hidden" name="idConnecte" value="{{ $utilisateur->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (el) {
      return new bootstrap.Tooltip(el)
    })

    // Gestion de la photo de profil dans le modal
    const photoContainer = document.getElementById('photoContainer');
    const photoInput = document.getElementById('photoProfil');
    const photoPreview = document.getElementById('photoPreview');
    const photoText = document.getElementById('photoText');
    const removePhoto = document.getElementById('removePhoto');
    const supprimerPhoto = document.getElementById('supprimerPhoto');

    photoContainer.addEventListener('click', () => { photoInput.click(); });

    photoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(ev){
                photoPreview.src = ev.target.result;
                photoPreview.style.display = 'block';
                if(photoText) photoText.style.display = 'none';
                if(removePhoto) removePhoto.style.display = 'flex';
                supprimerPhoto.value = '0';
            }
            reader.readAsDataURL(file);
        }
    });

    if(removePhoto){
        removePhoto.addEventListener('click', (e)=>{
            e.stopPropagation();
            photoPreview.src = '';
            photoPreview.style.display = 'none';
            if(photoText) photoText.style.display = 'block';
            photoInput.value = '';
            supprimerPhoto.value = '1';
            removePhoto.style.display = 'none';
        });
    }
</script>

</body>
</html>
