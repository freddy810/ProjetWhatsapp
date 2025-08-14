<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Connexion WhatsApp')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="icon" type="image/png" href="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg">

<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    background-color: #121b22;
    color: #e5e5e5;
    font-family: "Segoe UI", sans-serif;
}
.row.g-0 {
    height: 100%;
    display: flex;
    align-items: center;      /* Centre verticalement */
    justify-content: center;  /* Centre horizontalement */
}
.login-card {
    display: flex;
    align-items: center;    /* centre verticalement */
    justify-content: center; /* centre horizontalement */
}

body {
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    background-color: #1e2a33;
    border-radius: 15px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.5);
    max-width: 1000px;
    width: 90%;
    height: 90vh;
    display: flex;
    overflow: hidden;
}

.login-left, .login-right {
    flex: 1;
    padding: 50px 40px;
}

.login-left h2 {
    font-weight: bold;
    color: #25d366;
    text-align: center;
    margin-bottom: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.login-left h2 i {
    font-size: 2rem;
}

.form-control {
    border-radius: 10px;
    padding-left: 40px;
    background-color: #2a3a40;
    color: #e5e5e5;
    border: none;
}
.form-control::placeholder {
    color: #888;
}
.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #888;
}

.btn {
    border-radius: 10px;
}
.btn-primary {
    background-color: #25d366;
    border: none;
    color: #fff;
}
.btn-primary:hover { background-color: #1ebd57; }
.btn-success { background-color: #075e54; border: none; }
.btn-warning { background-color: #128c7e; border: none; color: #fff; }

.photo-container {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #2a3a40;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    cursor: pointer;
    margin: 0 auto 15px;
    position: relative;
}
.photo-container img { width: 100%; height: 100%; object-fit: cover; }
#removePhoto {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255,0,0,0.8);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: bold;
}

.login-right {
    background-color: #1b2b33;
    color: #e5e5e5;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.user-img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 50%;
    margin: 5px;
    border: 3px solid #25d366;
    transition: transform 0.3s;
}
.user-img:hover { transform: scale(1.1); }

@media (max-width: 768px) {
    .login-card { flex-direction: column; height: auto; }
    .login-left, .login-right { padding: 30px; }
}

/* Modals sombres façon WhatsApp */
.modal-content { background-color: #1e2a33; color: #e5e5e5; border-radius: 15px; }
.modal-header, .modal-footer { border: none; }
.modal-header .btn-close { filter: invert(1); }
.modal-body .form-control { background-color: #2a3a40; color: #e5e5e5; border: none; }
.modal-body .form-control::placeholder { color: #888; }
.modal-footer .btn-outline-light { color: #e5e5e5; border-color: #888; }
</style>
</head>
<body>

<div class="login-card">
    <div class="row g-0 w-100 h-100">
        <!-- Colonne gauche -->
        <div class="col-md-6 login-left">
            <h5 class="mb-4 text-center">Récemment connectés</h5>
            <div class="d-flex flex-wrap justify-content-center">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="user-img">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="user-img">
                <img src="https://randomuser.me/api/portraits/men/12.jpg" class="user-img">
                <img src="https://randomuser.me/api/portraits/women/65.jpg" class="user-img">
                <img src="https://randomuser.me/api/portraits/men/21.jpg" class="user-img">
            </div>
            <p class="mt-3 text-center">Rejoignez-les et connectez-vous maintenant !</p>
        </div>

        <!-- Colonne droite -->
        <div class="col-md-6 login-right">
            <h2><i class="bi bi-whatsapp"></i> WhatsApp Login</h2>

            {{-- Messages flash --}}
            @if(session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
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

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3 position-relative">
                    <i class="bi bi-telephone-fill input-icon"></i>
                    <input type="text" class="form-control" name="telephone" placeholder="Téléphone" value="{{ old('telephone') }}" required>
                </div>
                <div class="mb-3 position-relative">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" class="form-control" name="motDePasse" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </button>
            </form>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                    <i class="bi bi-person-plus-fill"></i> Créer un compte
                </button>
                <button type="button" class="btn btn-warning flex-fill" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                    <i class="bi bi-key-fill"></i> Mot de passe oublié
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création de compte -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <form method="POST" action="{{ route('utilisateur.register') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Créer un compte</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="photo-container" id="photoContainer">
            <img id="photoPreview" src="" style="display:none;">
            <span id="photoText">Ajouter</span>
            <span id="removePhoto">&times;</span>
          </div>
          <input type="file" id="photoProfil" name="photoProfil" accept="image/*" style="display:none;">
          <input type="text" name="nom" class="form-control mb-2" placeholder="Nom" required>
          <input type="text" name="prenom" class="form-control mb-2" placeholder="Prénom">
          <input type="date" name="dateNaissance" class="form-control mb-2" required>
          <input type="text" name="telephone" class="form-control mb-2" placeholder="Téléphone" required>
          <input type="password" name="motDePasse" class="form-control" placeholder="Mot de passe" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-success">Créer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Mot de passe oublié -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <form method="POST" action="{{ route('utilisateur.changerMotDePasse') }}">
        @csrf
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Mot de passe oublié</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="telephone" class="form-control mb-2" placeholder="Téléphone" required>
          <input type="password" name="nouveauMotDePasse" class="form-control mb-2" placeholder="Nouveau mot de passe" required>
          <input type="password" name="confirmationMotDePasse" class="form-control" placeholder="Confirmer le mot de passe" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-warning">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const photoContainer = document.getElementById('photoContainer');
const photoInput = document.getElementById('photoProfil');
const photoPreview = document.getElementById('photoPreview');
const photoText = document.getElementById('photoText');
const removePhoto = document.getElementById('removePhoto');

photoContainer.addEventListener('click', () => photoInput.click());
photoInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = ev => {
            photoPreview.src = ev.target.result;
            photoPreview.style.display = 'block';
            photoText.style.display = 'none';
            removePhoto.style.display = 'flex';
        }
        reader.readAsDataURL(file);
    }
});
removePhoto.addEventListener('click', e => {
    e.stopPropagation();
    photoPreview.src = '';
    photoPreview.style.display = 'none';
    photoInput.value = '';
    photoText.style.display = 'block';
    removePhoto.style.display = 'none';
});

// Ouverture automatique si session
@if(session('modal'))
    var modalId = "{{ session('modal') }}" === 'create' ? 'createAccountModal' : 'forgotPasswordModal';
    var modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
@endif
</script>
</body>
</html>
