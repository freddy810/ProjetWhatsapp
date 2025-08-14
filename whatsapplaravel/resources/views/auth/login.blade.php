<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Ventes')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Connexion</h2>

    <!-- Messages de succès ou d'erreur généraux -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any() && !session()->has('modal'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire de connexion -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
        </div>

        <div class="mb-3">
            <label for="motDePasse" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <!-- Boutons Création de compte et Mot de passe oublié -->
    <div class="mt-3 d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAccountModal">
            Créer un compte
        </button>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
            Mot de passe oublié
        </button>
    </div>
</div>

<!-- Modal Création de compte -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="{{ route('utilisateur.register') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Créer un compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Messages de succès/erreur pour ce modal -->
                @if(session('success_create'))
                    <div class="alert alert-success">{{ session('success_create') }}</div>
                @endif
                @if(session('error_create'))
                    <div class="alert alert-danger">{{ session('error_create') }}</div>
                @endif
                @if ($errors->any() && session('modal') === 'create')
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Champ Ajouter une photo -->
                <div class="mb-3 position-relative">
                    <label class="form-label d-block">Photo de profil</label>
                    <div id="photoContainer" style="width:100px; height:100px; border-radius:50%; background:#eee; display:flex; align-items:center; justify-content:center; cursor:pointer; margin:0 auto 15px; position:relative; overflow:hidden;">
                        <img id="photoPreview" src="" style="width:100%; height:100%; object-fit:cover; display:none;">
                        <span id="photoText">Ajouter une photo</span>
                        <span id="removePhoto" style="position:absolute; top:5px; right:5px; background:red; color:white; width:24px; height:24px; border-radius:50%; display:none; align-items:center; justify-content:center; cursor:pointer; font-weight:bold;">&times;</span>
                    </div>
                    <input type="file" id="photoProfil" name="photoProfil" accept="image/*" style="display:none;">
                </div>

                <!-- Autres champs -->
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="dateNaissance" class="form-control" value="{{ old('dateNaissance') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="motDePasse" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">Créer</button>
            </div>
        </form>        
    </div>
  </div>
</div>

<!-- Messages de succès/erreur pour ce modal -->
@if(session('success_password'))
<div class="alert alert-success">{{ session('success_password') }}</div>
@endif
@if(session('error_password'))
<div class="alert alert-danger">{{ session('error_password') }}</div>
@endif
@if ($errors->any() && session('modal') === 'forgot')
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Modal Mot de passe oublié -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" action="{{ route('utilisateur.changerMotDePasse') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Mot de passe oublié</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="nouveauMotDePasse" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="confirmationMotDePasse" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script pour gérer la photo -->
<script>
    const photoContainer = document.getElementById('photoContainer');
    const photoInput = document.getElementById('photoProfil');
    const photoPreview = document.getElementById('photoPreview');
    const photoText = document.getElementById('photoText');
    const removePhoto = document.getElementById('removePhoto');

    photoContainer.addEventListener('click', () => { photoInput.click(); });

    photoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(ev){
                photoPreview.src = ev.target.result;
                photoPreview.style.display = 'block';
                photoText.style.display = 'none';
                removePhoto.style.display = 'flex';
            }
            reader.readAsDataURL(file);
        }
    });

    removePhoto.addEventListener('click', (e)=>{
        e.stopPropagation();
        photoPreview.src = '';
        photoPreview.style.display = 'none';
        photoInput.value = '';
        photoText.style.display = 'block';
        removePhoto.style.display = 'none';
    });
</script>

<!-- Script pour rouvrir le modal si erreur ou succès -->
@if(session('modal'))
<script>
    var modalId = session('modal') === 'create' ? 'createAccountModal' : 'forgotPasswordModal';
    var modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
</script>
@endif

</body>
</html>
