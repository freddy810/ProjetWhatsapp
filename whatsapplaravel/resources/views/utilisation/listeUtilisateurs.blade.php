<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ventes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #2a3942;
        }
        ::-webkit-scrollbar-thumb {
            background: #8696a0;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #667781;
        }
        
        /* Animation for modals */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-animate {
            animation: fadeIn 0.2s ease-out forwards;
        }
    </style>
</head>
<body class="bg-[#111b21] text-[#d1d7db] min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <!-- Bouton retour -->
        <a href="{{ route('affichage.liste', ['id' => session('utilisateur')->id]) }}" class="inline-flex items-center px-4 py-2 bg-[#2a3942] hover:bg-[#374248] text-white rounded-lg mb-4 transition-colors duration-200">
            <i class="bi bi-arrow-left mr-2"></i> Retour
        </a>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-white">Liste des utilisateurs</h2>
            
            <!-- Bouton créer un nouveau contact -->
            <button class="flex items-center px-4 py-2 bg-[#00a884] hover:bg-[#06cf9c] text-white rounded-lg transition-colors duration-200" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg mr-2"></i> Nouveau contact
            </button>
        </div>

        @if(session('success'))
            <div class="p-3 mb-4 bg-green-800/50 text-green-100 rounded-lg border border-green-700/50">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 bg-red-800/50 text-red-100 rounded-lg border border-red-700/50">{{ session('error') }}</div>
        @endif

        <div class="bg-[#2a3942] rounded-xl overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#202c33] text-[#d1d7db]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Prénom</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Téléphone</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Date de naissance</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Photo</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#374248]">
                        @foreach($utilisateurs as $user)
                            <tr class="hover:bg-[#374248]/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->nom }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->prenom }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->telephone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->dateNaissance }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->photoProfil)
                                        <img src="{{ asset('images/profils/' . $user->photoProfil) }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <span class="text-[#8696a0]">Aucune</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Bouton Supprimer -->
                                    <button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm transition-colors duration-200" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Supprimer -->
                            <div class="modal-animate fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="deleteModal{{ $user->id }}">
                                <div class="bg-[#2a3942] rounded-lg w-full max-w-md mx-4">
                                    <form action="{{ route('utilisateurs.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="p-4 border-b border-[#374248]">
                                            <h3 class="text-lg font-medium text-white">Confirmation</h3>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-[#d1d7db]">Voulez-vous vraiment supprimer cet utilisateur : <strong class="text-white">{{ $user->nom }} {{ $user->prenom }}</strong> ?</p>
                                        </div>
                                        <div class="p-4 flex justify-end space-x-3 border-t border-[#374248]">
                                            <button type="button" class="px-4 py-2 bg-[#374248] hover:bg-[#47535a] text-white rounded-md transition-colors duration-200" onclick="closeModal('deleteModal{{ $user->id }}')">
                                                Annuler
                                            </button>
                                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200">
                                                Oui, supprimer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Créer nouveau contact -->
    <div class="modal-animate fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="createModal">
        <div class="bg-[#2a3942] rounded-lg w-full max-w-md mx-4">
            <form action="{{ route('utilisateur.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-4 border-b border-[#374248]">
                    <h3 class="text-lg font-medium text-white">Nouveau contact</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Nom</label>
                        <input type="text" name="nom" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-[#00a884]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Type</label>
                        <select name="type" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-[#00a884]">
                            <option value="administrateur">Administrateur</option>
                            <option value="simple_utilisateur">Simple Utilisateur</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Prénom</label>
                        <input type="text" name="prenom" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-[#00a884]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Téléphone</label>
                        <input type="text" name="telephone" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-[#00a884]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Date de naissance</label>
                        <input type="date" name="dateNaissance" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-[#00a884]" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Photo de profil</label>
                        <input type="file" name="photoProfil" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-1 text-white file:mr-4 file:py-1 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#00a884] file:text-white hover:file:bg-[#06cf9c]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#d1d7db] mb-1">Mot de passe</label>
                        <input type="password" name="motDePasse" class="w-full bg-[#374248] border border-[#47535a] rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-[#00a884]" required>
                    </div>
                </div>
                <div class="p-4 flex justify-end space-x-3 border-t border-[#374248]">
                    <button type="button" class="px-4 py-2 bg-[#374248] hover:bg-[#47535a] text-white rounded-md transition-colors duration-200" onclick="closeModal('createModal')">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#00a884] hover:bg-[#06cf9c] text-white rounded-md transition-colors duration-200">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Handle modal toggles
        document.addEventListener('DOMContentLoaded', function() {
            // For create button
            document.querySelector('[data-bs-target="#createModal"]').addEventListener('click', function() {
                openModal('createModal');
            });

            // For delete buttons
            document.querySelectorAll('[data-bs-target^="#deleteModal"]').forEach(button => {
                const modalId = button.getAttribute('data-bs-target').substring(1);
                button.addEventListener('click', function() {
                    openModal(modalId);
                });
            });

            // Close modals when clicking outside
            document.querySelectorAll('.modal-animate').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>