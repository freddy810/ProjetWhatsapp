@extends('layouts.app')
@section('title', 'Modifier message')
@section('content')

<div class="row mt-5">
    <div class="col-md-3"></div>
    <div class="col-md-6 bg-light mt-4 p-4">
        <form action="{{ route('messages.update', $message->id) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="contenues" class="form-label">contenues</label>
                    <input type="text" name="contenues" id="contenues" class="form-control" value="{{ old('contenues', $message->contenues) }}">
                    @if($errors->has('contenues'))
                        <p class="text-danger">{{ $errors->first('contenues') }}</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="statusMessage" class="form-label">statusMessage</label>
                    <select type="text" name="statusMessage" id="statusMessage" class="form-select" required>
                        <option value="lus" {{ old('typeMessage', $message->statusMessage) == 'lus' ? 'selected' : '' }}>Lus</option>
                        <option value="non_lus" {{ old('typeMessage', $message->statusMessage) == 'non_lus' ? 'selected' : '' }}>Non lus</option>
                    </select>
                    @if($errors->has('statusMessage'))
                        <p class="text-danger">{{ $errors->first('statusMessage') }}</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="typeMessage" class="form-label">typeMessage</label>
                    <select name="typeMessage" class="form-select" required>
                        <option value="texte" {{ old('typeMessage', $message->typeMessage) == 'texte' ? 'selected' : '' }}>Texte</option>
                        <option value="fichier" {{ old('typeMessage', $message->typeMessage) == 'fichier' ? 'selected' : '' }}>Fichier</option>
                    </select>
                    @if($errors->has('typeMessage'))
                        <p class="text-danger">{{ $errors->first('typeMessage') }}</p>
                    @endif
                </div>
            </div>

            {{-- Champ fichierMessage --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="fichierMessage" class="form-label">Fichier (image ou PDF)</label>
                    <input type="file" name="fichierMessage" id="fichierMessage" class="form-control">
                    @if($message->fichierMessage)
                        <div class="mt-2">
                            <strong>Fichier actuel :</strong><br>

                            @php
                                $extension = strtolower(pathinfo($message->fichierMessage, PATHINFO_EXTENSION));
                            @endphp

                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ asset('imagesMessages/' . $message->fichierMessage) }}" 
                                    alt="Fichier actuel" 
                                    style="max-width: 200px; max-height: 200px; border: 1px solid #ccc;">
                            @elseif($extension === 'pdf')
                                <embed src="{{ asset('imagesMessages/' . $message->fichierMessage) }}" 
                                    type="application/pdf" 
                                    width="100%" 
                                    height="400px">
                            @else
                                <a href="{{ asset('imagesMessages/' . $message->fichierMessage) }}" 
                                target="_blank">{{ $message->fichierMessage }}</a>
                            @endif
                        </div>
                    @endif

                    @if($errors->has('fichierMessage'))
                        <p class="text-danger">{{ $errors->first('fichierMessage') }}</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="dateMessage" class="form-label">dateMessage</label>
                    <input type="dateTime" name="dateMessage" id="dateMessage" class="form-control" value="{{ old('dateMessage', $message->dateMessage) }}">
                    @if($errors->has('dateMessage'))
                        <p class="text-danger">{{ $errors->first('dateMessage') }}</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Envoyeur</label>
                    <select name="utilisateurEnvoyeurMessage_id" class="form-select" required>
                        @foreach($utilisateurs as $utilisateur)
                            <option value="{{ $utilisateur->id }}" {{ old('utilisateurEnvoyeurMessage_id', $message->utilisateurEnvoyeurMessage_id) == $utilisateur->id ? 'selected' : '' }}>
                                {{ $utilisateur->nom }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('utilisateurEnvoyeurMessage_id'))
                        <p class="text-danger">{{ $errors->first('utilisateurEnvoyeurMessage_id') }}</p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Receveur</label>
                    <select name="utilisateurReceveurMessage_id" class="form-select" required>
                        @foreach($utilisateurs as $utilisateur)
                            <option value="{{ $utilisateur->id }}" {{ old('utilisateurReceveurMessage_id', $message->utilisateurReceveurMessage_id) == $utilisateur->id ? 'selected' : '' }}>
                                {{ $utilisateur->nom }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('utilisateurReceveurMessage_id'))
                        <p class="text-danger">{{ $errors->first('utilisateurReceveurMessage_id') }}</p>
                    @endif
                </div>
            </div>

            <div class="bouton mt-3" style="float: right;">
                <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
                <a type="button" href="{{ route('messages.index') }}" class="btn btn-danger mt-3">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
