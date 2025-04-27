@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Mon Portefeuille Crypto</h1>
    <div class="dash">
        <div class="card" style="background-color: rgb(0, 255, 255);">
            <h2 class="card-title">Valeur totale: {{ number_format($total_portfolio_value, 2) }} </h2>
        </div>
    </div>
    <div class="classement">
        <table>
            <thead>
                <tr>
                    <th>Cryptomonnaie</th>
                    <th>Symbole</th>
                    <th>Quantité</th>
                    <th>Prix actuel</th>
                    <th>Valeur totale</th>
                </tr>
            </thead>
            <tbody id="crypto-tbody">
                @foreach($wallets as $wallet)
                    <tr>
                        <td>{{ $wallet['crypto_name'] }}</td>
                        <td>{{ $wallet['symbol'] }}</td>
                        <td>{{ number_format($wallet['amount'], 8) }}</td>
                        <td>${{ number_format($wallet['current_price'], 2) }}</td>
                        <td>${{ number_format($wallet['total_value'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection
<script>
    // Fonction pour mettre à jour les données du portefeuille
    function updatePortfolio() {
        fetch('/portfolio-data') // URL de la route pour récupérer les données
            .then(response => response.json())
            .then(data => {
                // Mettre à jour la valeur totale
                document.querySelector('.card-title').textContent = `Valeur totale: $${data.total_portfolio_value} USD`;

                // Mettre à jour les lignes du portefeuille
                const tbody = document.getElementById('crypto-tbody');
                tbody.innerHTML = ''; // Réinitialiser les lignes existantes

                data.wallets.forEach(wallet => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${wallet.crypto_name}</td>
                        <td>${wallet.symbol}</td>
                        <td>${wallet.amount}</td>
                        <td>$${wallet.current_price}</td>
                        <td>$${wallet.total_value}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Erreur lors de la mise à jour du portefeuille:', error));
    }

    // Initialiser la mise à jour du portefeuille toutes les 10 secondes
    setInterval(updatePortfolio, 10000);

    // Mettre à jour immédiatement après le chargement de la page
    updatePortfolio();
</script>
