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
        <div class="form-login">
            <h2 class="logo">BitCoin</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('login') }}" class="" method="POST">
                @csrf
                <p class="index">Connexion</p>
                <div class="form-control">
                    <label for="">Email</label>
                    <input type="email" name="email" required required value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="">Password</label>
                    <input type="password" name="password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <button class="button submit">Se connecter</button>
            </form>
            <p class="link">vous n'avez pas encore de compte? <a href="{{ route('register') }}">s'inscrire</a></p>
        </div>
        <div class="form-illustration log">
            <img src="template/assets/image/login.jpg" alt="bitcoin">
        </div>
    </div>
</body>

</html>
