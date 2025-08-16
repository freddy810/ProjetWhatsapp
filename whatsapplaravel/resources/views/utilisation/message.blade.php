<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Clone - Dark Mode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .chat-container {
            background-image: url('https://web.whatsapp.com/img/bg-chat-tile-dark_a4be512e7195b6b733d9110b408f075d.png');
            background-repeat: repeat;
            background-color: #0a1014;
            background-blend-mode: overlay;
        }
        .message-received {
            border-top-left-radius: 0;
        }
        .message-sent {
            border-top-right-radius: 0;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .message-animation {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-transition {
            transition: all 0.3s ease-out;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 2px rgba(5, 165, 130, 0.2);
        }
        .dark-bg {
            background-color: #111b21;
        }
        .dark-header {
            background-color: #202c33;
        }
        .dark-input {
            background-color: #2a3942;
        }
        .dark-modal {
            background-color: #233138;
        }
        .dark-modal-header {
            background-color: #202c33;
            border-color: #2a3942;
        }
        .dark-modal-footer {
            border-color: #2a3942;
        }
    </style>
</head>
<body class="bg-gray-900">
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
    <div class="flex flex-col h-screen max-w-4xl mx-auto dark-bg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-3 dark-header text-gray-200 shadow-md">
            <div class="flex items-center space-x-3">
                <a href="{{ route('affichage.liste', ['id' => $idUtilisateurConnecter]) }}" class="text-gray-200 hover:text-gray-400 transition-colors">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                @if(strlen($photoProfilAutre) > 1)
                    <img src="{{ asset('images/profils/'.$photoProfilAutre) }}"
                         alt="Photo de profil"
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-600">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-gray-300 border-2 border-gray-600">
                        {{ $photoProfilAutre }}
                    </div>
                @endif
                <div>
                    <div class="font-semibold text-lg text-gray-100">{{ $autreUtilisateurNom }}</div>
                    <div class="text-xs text-gray-400 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        Online
                    </div>
                </div>
            </div>
            <div class="flex space-x-4 text-xl text-gray-300">
                <button class="hover:text-gray-400 transition-colors">
                    <i class="bi bi-search"></i>
                </button>
                <button class="hover:text-gray-400 transition-colors">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </div>
        </div>

        <!-- Chat area -->
        <div class="flex-1 overflow-y-auto p-4 chat-container scrollbar-hide" id="chatAvecAutreUtilisateur">
            @foreach($messages as $message)
                <!-- Date separator -->
                @if($loop->first || \Carbon\Carbon::parse($message['dateMessage'])->format('Y-m-d') !== \Carbon\Carbon::parse($messages[$loop->index-1]['dateMessage'])->format('Y-m-d'))
                    <div class="flex justify-center my-4">
                        <span class="bg-gray-800 px-3 py-1 rounded-full text-xs text-gray-400 shadow-sm">
                            {{ \Carbon\Carbon::parse($message['dateMessage'])->format('d F Y') }}
                        </span>
                    </div>
                @endif

                <!-- Message -->
                <div class="flex mb-4 {{ $message['receveur'] == $idUtilisateurConnecter ? 'justify-start' : 'justify-end' }} message-animation">
                    <div class="max-w-xs md:max-w-md lg:max-w-lg">
                        <div class="flex {{ $message['receveur'] == $idUtilisateurConnecter ? 'justify-start' : 'justify-end' }}">
                            @if ($message['receveur'] == $idUtilisateurConnecter)
                                <div class="bg-gray-800 rounded-lg py-2 px-3 message-received shadow-md relative group">
                                    @if(!empty($message['imageMessage']))
                                        <img src="{{ asset('imagesMessages/'.$message['imageMessage']) }}"
                                              class="rounded-lg mb-1 max-w-full max-h-64 object-cover">
                                    @else
                                        <p class="text-gray-100">{{ $message['contenu'] }}</p>
                                    @endif
                                    <div class="absolute -bottom-3 right-2 text-xs text-gray-400 flex items-center space-x-1">
                                        <span>{{ \Carbon\Carbon::parse($message['dateMessage'])->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="bg-[#005c4b] rounded-lg py-2 px-3 message-sent shadow-md relative group">
                                    @if(!empty($message['imageMessage']))
                                        <img src="{{ asset('imagesMessages/'.$message['imageMessage']) }}"
                                              class="rounded-lg mb-1 max-w-full max-h-64 object-cover">
                                    @else
                                        <p class="text-gray-100">{{ $message['contenu'] }}</p>
                                    @endif
                                    <div class="absolute -bottom-3 right-2 text-xs text-gray-300 flex items-center space-x-1">
                                        <span>{{ \Carbon\Carbon::parse($message['dateMessage'])->format('H:i') }}</span>
                                        <i class="bi bi-check2-all text-blue-400"></i>
                                    </div>
                                    @if(empty($message['imageMessage']))
                                        <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-1">
                                            <button class="text-gray-300 hover:text-gray-100 transition-colors"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modifierMessageModal"
                                                    data-message-id="{{ $message['id'] }}"
                                                    data-message-contenu="{{ $message['contenu'] }}">
                                                <i class="bi bi-pencil text-sm"></i>
                                            </button>
                                            <button class="text-gray-300 hover:text-gray-100 transition-colors"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#supprimerMessageModal"
                                                    data-route="{{ route('message.destroy', ['id' => $message['id']]) }}">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Input area -->
        <div class="dark-header p-3 border-t border-gray-700">
            <div class="flex items-center space-x-2">
                <label for="fichierMessageInput" class="dark-input rounded-full p-2 text-gray-400 hover:text-gray-200 cursor-pointer transition-colors hover:bg-gray-700">
                    <i class="bi bi-plus-lg"></i>
                </label>
                <label for="emojiPicker" class="dark-input rounded-full p-2 text-gray-400 hover:text-gray-200 cursor-pointer transition-colors hover:bg-gray-700">
                    <i class="bi bi-emoji-smile"></i>
                </label>
                <form id="formImageMessage" action="{{ route('message.enregistrement') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="fichierMessageInput" name="fichierMessage" accept="image/*"/>
                    <input type="hidden" name="typeMessage" value="fichier"/>
                    <input type="hidden" name="utilisateurEnvoyeurMessage_id" value="{{ $idUtilisateurConnecter }}"/>
                    <input type="hidden" name="utilisateurReceveurMessage_id" value="{{ $autreUtilisateurId }}"/>
                </form>
                
                <form action="{{ route('message.enregistrement') }}" method="POST" class="flex-1 flex items-center space-x-2">
                    @csrf
                    <input type="text" name="contenues" placeholder="Écrire un message..."
                           class="dark-input flex-1 rounded-full py-2 px-4 border-0 text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-[#00a884] input-focus transition-all">
                    <input type="hidden" name="typeMessage" value="texte"/>
                    <input type="hidden" name="utilisateurEnvoyeurMessage_id" value="{{ $idUtilisateurConnecter }}"/>
                    <input type="hidden" name="utilisateurReceveurMessage_id" value="{{ $autreUtilisateurId }}"/>
                    <button type="submit" class="bg-[#00a884] text-gray-100 rounded-full p-2 hover:bg-[#008069] transition-colors">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
                
                <button class="dark-input rounded-full p-2 text-gray-400 hover:text-gray-200 transition-colors hover:bg-gray-700">
                    <i class="bi bi-mic"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="modifierMessageModal" tabindex="-1" aria-labelledby="modifierMessageLabel" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none modal-transition">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto dark-modal rounded-md outline-none text-current">
                <form id="modifierMessageForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="utilisateurEnvoyeurMessage_id" value="{{ $idUtilisateurConnecter }}"/>
                    <input type="hidden" name="utilisateurReceveurMessage_id" value="{{ $autreUtilisateurId }}"/>
                    <input type="hidden" name="typeMessage" value="texte"/>
                    <input type="hidden" name="statusMessage" value="texte"/>
                    <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 dark-modal-header rounded-t-md">
                        <h5 class="text-xl font-medium leading-normal text-gray-200" id="modifierMessageLabel">Modifier le message</h5>
                        <button type="button" class="btn-close box-content w-4 h-4 p-1 text-gray-400 border-none rounded-none opacity-70 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-gray-200 hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Fermer">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body relative p-4">
                        <textarea name="contenues" id="modifierMessageTextarea" class="form-control w-full px-3 py-2 text-base font-normal text-gray-200 dark-input bg-clip-padding border border-solid border-gray-600 rounded transition ease-in-out m-0 focus:text-gray-200 focus:border-[#00a884] focus:outline-none" rows="3"></textarea>
                    </div>
                    <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 dark-modal-footer rounded-b-md">
                        <button type="button" class="px-4 py-2 bg-gray-700 text-gray-200 rounded-md hover:bg-gray-600 transition-colors mr-2" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-[#00a884] text-white rounded-md hover:bg-[#008069] transition-colors">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="supprimerMessageModal" tabindex="-1" aria-labelledby="supprimerMessageLabel" aria-hidden="true">
        <div class="modal-dialog relative w-auto pointer-events-none modal-transition">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto dark-modal rounded-md outline-none text-current">
                <form id="supprimerMessageForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 dark-modal-header rounded-t-md">
                        <h5 class="text-xl font-medium leading-normal text-gray-200" id="supprimerMessageLabel">Confirmer la suppression</h5>
                        <button type="button" class="btn-close box-content w-4 h-4 p-1 text-gray-400 border-none rounded-none opacity-70 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-gray-200 hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Fermer">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body relative p-4">
                        <p class="text-gray-300">Voulez-vous vraiment supprimer ce message ? Cette action est irréversible.</p>
                    </div>
                    <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 dark-modal-footer rounded-b-md">
                        <button type="button" class="px-4 py-2 bg-gray-700 text-gray-200 rounded-md hover:bg-gray-600 transition-colors mr-2" data-bs-dismiss="modal">Non</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Oui, supprimer</button>
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
            modifierModal.querySelector('#modifierMessageTextarea').focus();
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

        // Scroll to bottom of chat
        window.onload = function() {
            var chatContainer = document.getElementById('chatAvecAutreUtilisateur');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        };

        // Auto-resize textarea in modal
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('modifierMessageTextarea');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
        });

        // Smooth scroll for new messages
        function scrollToBottom() {
            const chatContainer = document.getElementById('chatAvecAutreUtilisateur');
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>