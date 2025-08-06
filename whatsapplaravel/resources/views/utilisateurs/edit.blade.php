
@extends('layouts.app')
@section('title', 'Creation vehicule')
@section('content')


        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6 bg-light mt-4 p-4">
                <form action="{{route('utilisateurs.update',  $utilisateur->id)}}" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nom" class="form-label">nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{old('nom', $utilisateur->nom)}}">
                            @if($errors->has('nom'))
                                <p class="text-danger">{{$errors->first('nom')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="prenom" class="form-label">prenom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" value="{{old('prenom', $utilisateur->prenom)}}">
                            @if($errors->has('prenom'))
                                <p class="text-danger">{{$errors->first('prenom')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="dateNaissance" class="form-label">dateNaissance</label>
                            <input type="date" name="dateNaissance" id="dateNaissance" class="form-control" value="{{old('dateNaissance', $utilisateur->dateNaissance)}}">
                            @if($errors->has('dateNaissance'))
                                <p class="text-danger">{{$errors->first('dateNaissance')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="telephone" class="form-label">telephone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" value="{{old('telephone', $utilisateur->telephone)}}">
                            @if($errors->has('telephone'))
                                <p class="text-danger">{{$errors->first('telephone')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="photoProfil" class="form-label">Photo de profil actuelle</label>
                            @if($utilisateur->photoProfil)
                                <div class="mb-2">
                                    <img src="{{ asset('images/profils/' . $utilisateur->photoProfil) }}" alt="Photo de profil" width="120" class="img-thumbnail rounded">
                                </div>
                            @else
                                <p class="text-muted">Aucune photo actuellement.</p>
                            @endif
                    
                            <label for="photoProfil" class="form-label mt-2">Changer la photo</label>
                            <input type="file" name="photoProfil" id="photoProfil" class="form-control">
                            @if($errors->has('photoProfil'))
                                <p class="text-danger">{{ $errors->first('photoProfil') }}</p>
                            @endif
                        </div>
                    </div>
                    

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="en_ligne" {{ $utilisateur->status == 'en_ligne' ? 'selected' : '' }}>En ligne</option>
                                <option value="hors_ligne" {{ $utilisateur->status == 'hors_ligne' ? 'selected' : '' }}>Hors ligne</option>
                            </select>
                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="utilisateur_simple" {{ $utilisateur->type == 'utilisateur_simple' ? 'selected' : '' }}>Utilisateur simple</option>
                                <option value="administrateur" {{ $utilisateur->type == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                            @if($errors->has('type'))
                                <p class="text-danger">{{ $errors->first('type') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="motDePasse" class="form-label">motDePasse</label>
                            <input type="password" name="motDePasse" id="motDePasse" class="form-control" value="{{old('motDePasse', $utilisateur->motDePasse)}}">
                            @if($errors->has('motDePasse'))
                                <p class="text-danger">{{$errors->first('motDePasse')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="bouton mt-3" style="float: right;">
                        <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                        <a type="button" href="{{route('utilisateurs.index')}}" class="btn btn-danger mt-3">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

  @endsection

 