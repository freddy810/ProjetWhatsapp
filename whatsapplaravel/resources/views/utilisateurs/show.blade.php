
@extends('layouts.app')
@section('title', 'Creation vehicule')
@section('content')


        <div class="row mt-5">
            <div class="col-md-3"></div>
            <div class="col-md-6 bg-light mt-4 p-4">
                <form action="" method="POST" enctype="multipart/form-data">
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
                            <label for="photoProfil" class="form-label">photoProfil</label>
                            <input type="file" name="photoProfil" id="photoProfil" class="form-control" value="{{old('photoProfil', $utilisateur->photoProfil)}}">
                            @if($errors->has('photoProfil'))
                                <p class="text-danger">{{$errors->first('photoProfil')}}</p'>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="status" class="form-label">status</label>
                            <input type="text" name="status" id="status" class="form-control" value="{{old('status', $utilisateur->status)}}">
                            @if($errors->has('status'))
                                <p class="text-danger">{{$errors->first('status')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="type" class="form-label">type</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{old('type', $utilisateur->type)}}">
                            @if($errors->has('type'))
                                <p class="text-danger">{{$errors->first('type')}}</p'>
                            @endif
                        </div>
                    </div>

                    <div class="bouton mt-3" style="float: right;">
                        <a type="button" href="{{route('utilisateurs.index')}}" class="btn btn-danger mt-3">OK</a>
                    </div>
                </form>
            </div>
        </div>

  @endsection

 