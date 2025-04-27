@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <div class="form-container sm">
        <h2>Mon Profil</h2>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('POST')

            <div class="form-control">
                <label for="firstName">Prénom</label>
                <input type="text" id="firstName" name="firstName" value="{{ old('firstName', $user['first_name']) }}">
            </div>

            <div class="form-control">
                <label for="lastName">Nom</label>
                <input type="text" id="lastName" name="lastName" value="{{ old('lastName', $user['last_name']) }}">
            </div>

            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user['email']) }}">
            </div>

            <div class="form-control">
                <label for="phone">Téléphone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user['phone'] ?? '') }}">
            </div>

            <div class="button-div">
                <button type="reset" class="cancel-btn">Annuler</button>
                <button type="submit" class="achat-btn">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection