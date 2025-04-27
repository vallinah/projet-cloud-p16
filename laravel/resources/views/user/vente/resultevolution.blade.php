@extends('user.layout.layout-user')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mx-auto px-4 py-8">
    <h1 id="page">Evolution crypto {{ $cryptoname->name }}</h1>
    <div class="bg-white rounded-lg shadow-xl overflow-hidden p-4">
        <h2 class="text-xl font-semibold border-b pb-2">Historique des prix (Derniers 10 changements)</h2>
        <div class="h-48"> <!-- Réduit la taille du graphique -->
            <canvas id="cryptoPriceChart"></canvas>
        </div>
    </div>

    <div class="mt-8 flex justify-center space-x-4">
        <!-- Formulaire Acheter -->
        <button onclick="showForm('buy')" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Acheter</button>

        <!-- Formulaire Vendre -->
        @if (!$userHasCrypto->isEmpty())
            <button onclick="showForm('sell')" class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600">Vendre</button>
        @endif
    </div>

    <!-- Formulaire dynamique pour l'achat -->
    <div id="buyForm" style="display: none; margin-top: 20px;" class="flex justify-center">
        <form id="buyCryptoForm" method="post">
            @csrf
            <input type="hidden" name="crypto_id" value="{{ $crypto_id }}">
            <input type="hidden" name="action" value="buy">
            
            <label for="buy_quantity" class="mr-2">Quantité à acheter :</label>
            <input type="number" id="buy_quantity" name="quantity" required>
            
            <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600">Acheter</button>
            <button type="button" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600" onclick="cancelForm()">Annuler</button>
        </form>
    </div>

    <!-- Formulaire dynamique pour la vente -->
    <div id="sellForm" style="display: none; margin-top: 20px;" class="flex justify-center">
        <form id="sellCryptoForm" method="post">
            @csrf
            <input type="hidden" name="crypto_id" value="{{ $crypto_id }}">
            <input type="hidden" name="action" value="sell">
            
            <label for="sell_quantity" class="mr-2">Quantité à vendre :</label>
            <input type="number" id="sell_quantity" name="quantity" max="{{ $userHasCrypto->first()->amount ?? 0 }}" required>
                <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600">Vendre</button>
                <button type="button" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600" onclick="cancelForm()">Annuler</button>
            </div>
        </form>
    </div>
</div>


    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cryptoId = "{{ $crypto_id }}";
        let cryptoChart = null;

        async function updateChart() {
            try {               
                const response = await fetch(`/evolution-prices/${cryptoId}`);
                const data = await response.json();
                const priceHistory = data.priceHistory;

                if (!priceHistory || priceHistory.length === 0) {
                    console.warn("Aucune donnée à afficher !");
                    return;
                }

                const labels = priceHistory.map(item => new Date(item.change_date));
                const prices = priceHistory.map(item => parseFloat(item.new_price));

                const ctx = document.getElementById('cryptoPriceChart');
                if (!ctx) {
                    console.error("Canvas introuvable !");
                    return;
                }

                if (cryptoChart) {
                    cryptoChart.data.labels = labels;
                    cryptoChart.data.datasets[0].data = prices;
                    cryptoChart.update();
                } else {
                    cryptoChart = new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Prix de la Crypto (USD)',
                                data: prices,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderWidth: 2,
                                tension: 0.4,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: { type: 'time', time: { unit: 'minute', tooltipFormat: 'll HH:mm' } },
                                y: { title: { display: true, text: 'Prix (USD)' } }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error("Erreur lors de la récupération des données :", error);
            }
        }

        updateChart();
        setInterval(updateChart, 10000);
        function showForm(type) {
            $('#buyForm, #sellForm').hide();
            if (type === 'buy') $('#buyForm').show();
            else if (type === 'sell') $('#sellForm').show();
        }

        // Fonction pour cacher les formulaires
        function cancelForm() {
            $('#buyForm, #sellForm').hide();
        }
        window.showForm = showForm;
        window.cancelForm = cancelForm;

        // Envoi AJAX pour éviter rechargement total
        $('#buyCryptoForm, #sellCryptoForm').submit(function (e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: "{{ route('vente.sellCrypto') }}",
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    alert("Transaction réussie !");
                    cancelForm();
                    updateChart(); // Met à jour le graphique après la transaction
                },
                error: function() {
                    alert("Une erreur s'est produite, veuillez réessayer.");
                }
            });
        });
    });
    </script>

</body>
</html>
@endsection
