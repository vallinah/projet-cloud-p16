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
            <h2 class="logo">BitCoin Admin</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('admin') }}" class="" method="POST">
                @csrf
                <p class="index">Connexion</p>
                <div class="form-control">
                    <label for="">Login</label>
                    <input type="login" name="login" required value="admin">
                    @error('login')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-control">
                    <label for="">Password</label>
                    <input type="password" name="password" required value="admin">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <button class="button submit">Se connecter</button>
            </form>
        </div>
        <div class="form-illustration log">
            <img src="template/assets/image/login.jpg" alt="bitcoin">
        </div>
    </div>
</body>

</html>