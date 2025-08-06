
@extends('layouts.app')
@section('title', 'Creation vehicule')
@section('content')

        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6 bg-light mt-4 p-4">
                <form action="{{route('contactsUtilisateurs.update', $contactsUtilisateur->id)}}" method="POST" enctype="multipart/form-data">
                @csrf 
                @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="nom" class="form-label">nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{old('nom', $contactsUtilisateur->nom)}}">
                            @if($errors->has('nom'))
                                <p class="text-danger">{{$errors->first('nom')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="prenom" class="form-label">prenom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" value="{{old('prenom', $contactsUtilisateur->prenom)}}">
                            @if($errors->has('prenom'))
                                <p class="text-danger">{{$errors->first('prenom')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="numPhone" class="form-label">numPhone</label>
                            <input type="text" name="numPhone" id="numPhone" class="form-control" value="{{old('numPhone', $contactsUtilisateur->numPhone)}}">
                            @if($errors->has('numPhone'))
                                <p class="text-danger">{{$errors->first('numPhone')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Envoyeur</label>
                            <select name="utilisateurPossedantContact_id" class="form-select" required>
                                @foreach($utilisateurs as $utilisateur)
                                    <option value="{{ $utilisateur->id }}" {{ $utilisateur->id == $contactsUtilisateur->utilisateurPossedantContact_id ? 'selected' : '' }}>
                                        {{ $utilisateur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="bouton mt-3" style="float: right;">
                        <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                        <a type="button" href="{{route('contactsUtilisateurs.index')}}" class="btn btn-danger mt-3">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

  @endsection

 