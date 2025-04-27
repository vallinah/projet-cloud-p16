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
            <form id="pinForm" action="{{ route('check-pin-code') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <p class="index">Code de validation</p>
                <div class="key-form">
                    <input type="text" maxlength="1" size="1" class="pin-input" onkeyup="moveToNext(this, 'input2')"
                        id="input1" required>
                    <input type="text" maxlength="1" size="1" class="pin-input" onkeyup="moveToNext(this, 'input3')"
                        id="input2" required>
                    <input type="text" maxlength="1" size="1" class="pin-input" onkeyup="moveToNext(this, 'input4')"
                        id="input3" required>
                    <input type="text" maxlength="1" size="1" class="pin-input" onkeyup="moveToNext(this, 'input5')"
                        id="input4" required>
                    <input type="text" maxlength="1" size="1" class="pin-input" onkeyup="moveToNext(this, 'input6')"
                        id="input5" required>
                    <input type="text" maxlength="1" size="1" class="pin-input" id="input6" required>
                </div>
                <input type="hidden" name="pinCode" id="pinCodeInput">
                <button class="button submit" onclick="mergePinCode()">Verifier</button>
            </form>
            <p class="link">vous n'avez pas encore recu de code? <a href="">r'envoyer le code</a></p>
        </div>
        <div class="form-illustration log">
            <img src="template/assets/image/key.jpg" alt="bitcoin">
        </div>
    </div>
</body>
<script>
    function moveToNext(current, nextFieldId) {
        if (current.value.length === 1) {
            document.getElementById(nextFieldId)?.focus();
        }
    }
    function mergePinCode() {
        const inputs = document.querySelectorAll('.pin-input');
        const pinCode = Array.from(inputs).map(input => input.value).join('');
        document.getElementById('pinCodeInput').value = pinCode;
    }
    document.addEventListener('DOMContentLoaded', () => {
        const pinInputs = document.querySelectorAll('.pin-input');
        const pinForm = document.getElementById('pinForm');
        const errorMessage = document.getElementById('errorMessage');
        const resendLink = document.getElementById('resendLink');

        // Gérer le focus automatique entre les champs PIN
        pinInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < pinInputs.length - 1) {
                    pinInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    pinInputs[index - 1].focus();
                }
            });
        });


    });
</script>

</html>