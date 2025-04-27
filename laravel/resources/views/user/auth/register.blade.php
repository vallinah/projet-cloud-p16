<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="template/assets/css/login.css">
    <title>Document</title>
</head>

<body class="body-login">
    <div class="log-div">
        <div class="form-sign">
            <h2 class="logo">BitCoin</h2>
            @if($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('register') }}" class="" method="POST">
                @csrf
                <p class="index">Inscription</p>
                <div class="h-form">
                    <div class="form-control">
                        <label for="">Nom</label>
                        <input type="text" name="lastName" required>
                        @error('lastName')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-control">
                        <label for="">Prenom</label>
                        <input type="text" name="firstName" required>
                        @error('firstName')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-control">
                    <label for="">Email</label>
                    <input type="email" name="email" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="">Date de naissance</label>
                    <input type="date" name="dateOfBirth" required>
                    @error('dateOfBirth')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="">Mot de passe</label>
                    <input type="password" name="password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <button class="button submit">S'inscrire</button>
            </form>
            <p class="link">vous avez deja un compte? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
        <div class="form-illustration sign">
            <img src="template/assets/image/login.jpg" alt="bitcoin">
        </div>
    </div>
</body>

</html>
