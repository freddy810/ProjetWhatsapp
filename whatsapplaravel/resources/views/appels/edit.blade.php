
@extends('layouts.app')
@section('title', 'Creation vehicule')
@section('content')


        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6 bg-light mt-4 p-4">
                <form action="{{route('appels.update',  $appel->id)}}" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT')
                
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="appel_manquer" {{ old('status', $appel->status ?? '') == 'appel_manquer' ? 'selected' : '' }}>Appel manqué</option>
                                <option value="appel_reussit" {{ old('status', $appel->status ?? '') == 'appel_reussit' ? 'selected' : '' }}>Appel réussit</option>
                                <option value="appel_decliné" {{ old('status', $appel->status ?? '') == 'appel_decliné' ? 'selected' : '' }}>Appel décliné</option>
                            </select>
                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>
                

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="audio" {{ old('type', $appel->type ?? '') == 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="video" {{ old('type', $appel->type ?? '') == 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                            @if($errors->has('type'))
                                <p class="text-danger">{{ $errors->first('type') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="dateAppel" class="form-label">dateAppel</label>
                            <input type="date" name="dateAppel" id="dateAppel" class="form-control" value="{{old('dateAppel', $appel->dateAppel)}}">
                            @if($errors->has('dateAppel'))
                                <p class="text-danger">{{$errors->first('dateAppel')}}</p'>
                            @endif
                        </div>
                    </div>
                    

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Envoyeur</label>
                            <select name="utilisateurEnvoyeur_id" class="form-select" required>
                                @foreach($utilisateurs as $utilisateur)
                                    <option value="{{ $utilisateur->id }}" {{ $utilisateur->id == $appel->utilisateurEnvoyeur_id ? 'selected' : '' }}>
                                        {{ $utilisateur->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('utilisateurEnvoyeur_id'))
                                <p class="text-danger">{{ $errors->first('utilisateurEnvoyeur_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Receveur</label>
                            <select name="utilisateurReceveur_id" class="form-select" required>
                                @foreach($utilisateurs as $utilisateur)
                                    <option value="{{ $utilisateur->id }}" {{ $utilisateur->id == $appel->utilisateurReceveur_id ? 'selected' : '' }}>
                                        {{ $utilisateur->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('utilisateurReceveur_id'))
                                <p class="text-danger">{{ $errors->first('utilisateurReceveur_id') }}</p>
                             @endif
                        </div>
                    </div>

                    <div class="bouton mt-3" style="float: right;">
                        <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                        <a type="button" href="{{route('appels.index')}}" class="btn btn-danger mt-3">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

  @endsection

 