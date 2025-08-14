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

<div class="container mt-4">
    <!-- Bouton retour -->
    <a href="{{ route('affichage.liste', ['id' => session('utilisateur')->id]) }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Retour
    </a>

    <h2>Liste des utilisateurs</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Bouton créer un nouveau contact -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-lg"></i> Nouveau contact
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Date de naissance</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($utilisateurs as $user)
                <tr>
                    <td>{{ $user->nom }}</td>
                    <td>{{ $user->prenom }}</td>
                    <td>{{ $user->telephone }}</td>
                    <td>{{ $user->dateNaissance }}</td>
                    <td>
                        @if($user->photoProfil)
                            <img src="{{ asset('images/profils/' . $user->photoProfil) }}" width="50" height="50" style="border-radius: 50%;">
                        @else
                            <span>Aucune</span>
                        @endif
                    </td>
                    <td>
                        <!-- Bouton Supprimer -->
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                            Supprimer
                        </button>
                    </td>
                </tr>

                <!-- Modal Supprimer -->
                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('utilisateurs.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment supprimer cet utilisateur : <strong>{{ $user->nom }} {{ $user->prenom }}</strong> ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form id="formSuppression" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Oui</button>
                                    </form>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Créer nouveau contact -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('utilisateur.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>type</label>
                        <select for="type" name="type" class="form-select">
                            <option value="administrateur">Administrateur</option>
                            <option value="simple_utilisateur">Simple Utilisateur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Prénom</label>
                        <input type="text" name="prenom" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Date de naissance</label>
                        <input type="date" name="dateNaissance" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Photo de profil</label>
                        <input type="file" name="photoProfil" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Mot de passe </label>
                        <input type="password" name="motDePasse" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
