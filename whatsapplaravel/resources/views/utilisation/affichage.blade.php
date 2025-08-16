<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Ventes')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            100: '#1e293b',
                            200: '#1e293b',
                            300: '#334155',
                            400: '#475569',
                            500: '#64748b',
                            600: '#94a3b8',
                            700: '#cbd5e1',
                            800: '#e2e8f0',
                            900: '#f1f5f9',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom styles that can't be done with Tailwind */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            color: #fff;
            object-fit: cover;
        }
        
        #photoContainer {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            margin: 0 auto 15px auto;
            border: 2px solid #4b5563;
        }
        
        #photoContainer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        #removePhoto {
            position: absolute;
            top: 5px;
            right: 15px;
            background: #dc2626;
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
        
        /* WhatsApp-like scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        
        /* Animation for new messages */
        @keyframes pulse {
            0% { background-color: #1e4040; }
            50% { background-color: #1e4f4f; }
            100% { background-color: #1e4040; }
        }
        
        .unread-message {
            animation: pulse 2s infinite;
        }

        .modal-content {
            background-color: #1e293b;
            color: #e2e8f0;
        }

        .form-control, .form-select {
            background-color: #1e293b;
            color: #e2e8f0;
            border-color: #4b5563;
        }

        .form-control:focus, .form-select:focus {
            background-color: #1e293b;
            color: #e2e8f0;
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }

        .btn-close {
            filter: invert(1);
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex dark">
    <!-- WhatsApp-like sidebar -->
    <div class="w-full md:w-1/3 lg:w-1/4 bg-gray-800 border-r border-gray-700 flex flex-col h-screen">
        <!-- Header -->
        <div class="bg-gray-800 p-3 flex justify-between items-center border-b border-gray-700">
            <div class="flex items-center space-x-3">
                @if(!empty($utilisateur->photoProfil))
                    <img src="{{ asset('images/profils/' . $utilisateur->photoProfil) }}" alt="Profil" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="user-avatar bg-green-600">{{ strtoupper(substr($utilisateur->nom, 0, 1)) }}</div>
                @endif
                <span class="font-semibold text-gray-200">{{ $utilisateur->nom }}</span>
            </div>
            
            <div class="flex space-x-2">
                @if($utilisateur->type === "administrateur")
                    <a href="{{ route('affichage.listeAdmin', ['id' => session('utilisateur')->id]) }}" class="p-2 rounded-full hover:bg-gray-700 text-gray-300">
                        <i class="bi bi-people-fill"></i>
                    </a>
                @endif
                
                <button type="button" class="p-2 rounded-full hover:bg-gray-700 text-gray-300"
                     data-bs-toggle="modal" data-bs-target="#modifierInfosModal">
                    <i class="bi bi-pencil-square"></i>
                </button>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 rounded-full hover:bg-gray-700 text-gray-300">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Search bar -->
        <div class="p-3 bg-gray-800">
            <div class="relative">
                <input type="text" placeholder="Rechercher un contact..."
                     class="w-full py-2 px-4 pl-10 bg-gray-700 rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200 placeholder-gray-400">
                <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Chat list -->
        <div class="flex-1 overflow-y-auto">
            <ul class="divide-y divide-gray-700">
                @foreach($resultats as $resultat)
                    <li class="{{ $resultat['statusMessage'] == 'non_lus' ? 'unread-message' : 'hover:bg-gray-700' }}">
                        <a href="{{ route('message.message', ['idConnecter'=> $utilisateur->id,'id1' => $resultat['idEnvoyeur'], 'id2' => $resultat['idReceveur']]) }}"
                            class="flex items-center p-3">
                            @if (mb_strlen($resultat['imageUtilisateurChat']) > 1)
                                <img src="{{ asset('images/profils/' . $resultat['imageUtilisateurChat']) }}"
                                      alt="Profil" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="user-avatar bg-green-600">{{ strtoupper($resultat['imageUtilisateurChat']) }}</div>
                            @endif
                            
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-200">{{ $resultat['nomUtilisateurChat'] }}</span>
                                    <span class="text-xs text-gray-400">{{ $resultat['heure'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-sm {{ $resultat['statusMessage'] == 'non_lus' ? 'font-bold text-gray-200' : 'text-gray-400' }} truncate max-w-[180px]">
                                        {{ $resultat['contenu'] }}
                                    </p>
                                    @if($resultat['statusMessage'] == 'non_lus')
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- Empty chat area (like WhatsApp) -->
    <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-gray-900">
        <div class="w-64 text-center">
            <div class="p-4 bg-gray-800 rounded-full inline-block mb-4">
                <i class="bi bi-chat-dots text-green-500 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-light text-gray-300 mb-2">WhatsApp Web</h2>
            <p class="text-gray-400 mb-6">Sélectionnez une conversation pour commencer à discuter</p>
        </div>
    </div>

    <!-- Error messages -->
    @if(session('error'))
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if (session('info'))
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-blue-900 border border-blue-700 text-blue-100 px-4 py-3 rounded">
            {{ session('info') }}
        </div>
    </div>
    @endif

    <!-- Edit User Info Modal -->
    <div class="modal fade" id="modifierInfosModal" tabindex="-1" aria-labelledby="modifierInfosLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('utilisateur.update', $utilisateur->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-green-600 text-white">
                    <h5 class="modal-title" id="modifierInfosLabel">Modifier mes informations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Profile photo -->
                    <div class="mb-3 position-relative">
                        <label class="form-label d-block text-gray-300">Photo de profil</label>
                        <div id="photoContainer">
                            @if(!empty($utilisateur->photoProfil))
                                <img id="photoPreview" src="{{ asset('images/profils/' . $utilisateur->photoProfil) }}">
                            @else
                                <img id="photoPreview" src="" style="display:none;">
                            @endif
                            <span id="photoText" @if(!empty($utilisateur->photoProfil)) style="display:none;" @endif class="text-gray-400">Ajouter une image</span>
                            <span id="removePhoto" @if(empty($utilisateur->photoProfil)) style="display:none;" @endif>&times;</span>
                        </div>
                                                        
                        <input type="file" id="photoProfil" name="photoProfil" accept="image/*" style="display:none;">
                        <input type="hidden" name="supprimerPhoto" id="supprimerPhoto" value="0">
                    </div>

                    <!-- Other fields -->
                    <div class="mb-3">
                        <label for="nom" class="form-label text-left block mb-1 text-gray-300">Nom</label>
                        <input type="text" class="form-control w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                id="nom" name="nom" value="{{ $utilisateur->nom }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label text-left block mb-1 text-gray-300">Prénom</label>
                        <input type="text" class="form-control w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                id="prenom" name="prenom" value="{{ $utilisateur->prenom }}">
                    </div>
                    <div class="mb-3">
                        <label for="dateNaissance" class="form-label text-left block mb-1 text-gray-300">Date de naissance</label>
                        <input type="date" class="form-control w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                id="dateNaissance" name="dateNaissance" value="{{ $utilisateur->dateNaissance }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label text-left block mb-1 text-gray-300">Téléphone</label>
                        <input type="text" class="form-control w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                id="telephone" name="telephone" value="{{ $utilisateur->telephone }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="motDePasse" class="form-label text-left block mb-1 text-gray-300">Mot de passe</label>
                        <input type="text" class="form-control w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                id="motDePasse" name="motDePasse" value="{{ $utilisateur->motDePasse }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="px-4 py-2 bg-gray-600 text-gray-200 rounded-md hover:bg-gray-500"
                             data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Sauvegarder</button>
                </div>
            </form>
        </div>
      </div>
    </div>

    <!-- New Chat Button -->
    <button type="button" class="fixed bottom-6 right-6 w-14 h-14 bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-green-700 transition-colors duration-200"
            title="Nouvelle discussion"
            data-bs-toggle="modal" 
            data-bs-target="#nouvelleDiscussionModal">
        <i class="bi bi-pencil-square text-xl"></i>
    </button>

    <!-- New Chat Modal -->
    <div class="modal fade" id="nouvelleDiscussionModal" tabindex="-1" aria-labelledby="nouvelleDiscussionLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('nouvelle.discussion') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-green-600 text-white">
                    <h5 class="modal-title" id="nouvelleDiscussionLabel">Nouvelle discussion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="telephone" class="block mb-1 text-gray-300">Numéro de téléphone</label>
                        <input type="text" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                id="telephone" name="telephone" placeholder="Entrer le numéro...">
                    </div>
                    <div class="mb-4 flex items-start gap-2">
                        <textarea class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 text-gray-200"
                                   id="message" name="contenu" rows="3" placeholder="Écrire un message..."></textarea>
                        <label class="inline-flex items-center justify-center w-10 h-10 bg-gray-600 text-gray-300 rounded-md hover:bg-gray-500 cursor-pointer">
                            <i class="bi bi-paperclip"></i>
                            <input type="file" id="fichierMessage" name="fichierMessage" accept="image/*" hidden onchange="this.form.submit()">
                        </label>
                    </div>
                    <input type="hidden" name="idConnecte" value="{{ $utilisateur->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="px-4 py-2 bg-gray-600 text-gray-200 rounded-md hover:bg-gray-500"
                             data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Envoyer</button>
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

        // Profile photo management
        const photoContainer = document.getElementById('photoContainer');
        const photoInput = document.getElementById('photoProfil');
        const photoPreview = document.getElementById('photoPreview');
        const photoText = document.getElementById('photoText');
        const removePhoto = document.getElementById('removePhoto');
        const supprimerPhoto = document.getElementById('supprimerPhoto');

        photoContainer.addEventListener('click', () => photoInput.click());

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