
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
                            <label for="status" class="form-label">status</label>
                            <input type="text" name="status" id="status" class="form-control" value="{{old('status', $appel->status)}}">
                            @if($errors->has('status'))
                                <p class="text-danger">{{$errors->first('status')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="type" class="form-label">type</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{old('type', $appel->type)}}">
                            @if($errors->has('type'))
                                <p class="text-danger">{{$errors->first('type')}}</p'>
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
                        </div>
                    </div>

                    <div class="bouton mt-3" style="float: right;">
                        <a type="button" href="{{route('appels.index')}}" class="btn btn-danger mt-3">OK</a>
                    </div>
                </form>
            </div>
        </div>

  @endsection

 