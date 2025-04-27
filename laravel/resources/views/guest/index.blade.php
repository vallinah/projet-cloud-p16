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
            <h2 class="logo">BitCoin connexion</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('login') }}" class="" method="GET">
                <button class="button submit">Se connecter en tant qu'user</button>
            </form>
            <form action="{{ route('admin') }}" class="" method="GET">
                <button class="button submit">Se connecter en tant qu'admin</button>
            </form>
        </div>
        <div class="form-illustration log">
            <img src="template/assets/image/login.jpg" alt="bitcoin">
        </div>
    </div>
</body>

</html>