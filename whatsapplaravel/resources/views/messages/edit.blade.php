
@extends('layouts.app')
@section('title', 'Creation vehicule')
@section('content')

        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6 bg-light mt-4 p-4">
                <form action="{{route('messages.update', $message->id)}}" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="contenues" class="form-label">contenues</label>
                            <input type="text" name="contenues" id="contenues" class="form-control" value="{{old('contenues', $message->contenues)}}">
                            @if($errors->has('contenues'))
                                <p class="text-danger">{{$errors->first('contenues')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="typeMessage" class="form-label">Type de message</label>
                            <select name="typeMessage" class="form-select" required>
                                <option value="texte" {{ $message->typeMessage == 'texte' ? 'selected' : '' }}>Texte</option>
                                <option value="audio" {{ $message->typeMessage == 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="video" {{ $message->typeMessage == 'video' ? 'selected' : '' }}>Vidéo</option>
                                <option value="fichier" {{ $message->typeMessage == 'fichier' ? 'selected' : '' }}>Fichier</option>
                            </select>
                            @if($errors->has('typeMessage'))
                                <p class="text-danger">{{ $errors->first('typeMessage') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="dateMessage" class="form-label">dateMessage</label>
                            <input type="date" name="dateMessage" id="dateMessage" class="form-control" value="{{old('dateMessage', $message->dateMessage)}}">
                            @if($errors->has('dateMessage'))
                                <p class="text-danger">{{$errors->first('dateMessage')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Envoyeur</label>
                            <select name="utilisateurEnvoyeurMessage_id" class="form-select" required>
                                @foreach($utilisateurs as $utilisateur)
                                    <option value="{{ $utilisateur->id }}" {{ $utilisateur->id == $message->utilisateurEnvoyeurMessage_id ? 'selected' : '' }}>
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
                                    <option value="{{ $utilisateur->id }}" {{ $utilisateur->id == $message->utilisateurReceveurMessage_id ? 'selected' : '' }}>
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
                        <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                        <a type="button" href="{{route('messages.index')}}" class="btn btn-danger mt-3">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

  @endsection

 