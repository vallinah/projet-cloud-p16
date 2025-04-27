
// Attendre que le DOM soit chargé avant d'exécuter le script
document.addEventListener("DOMContentLoaded", function () {
    // Récupérer le canvas
    const ctx = document.getElementById('Chart2').getContext('2d');

    // Créer un graphique avec Chart.js
    const myChart = new Chart(ctx, {
        type: 'pie', // Type de graphique (bar, line, pie, etc.)
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'], // Labels des données
            datasets: [{
                label: 'Ventes Mensuelles',
                data: [12, 19, 3, 5, 2, 3], // Données à afficher
                backgroundColor: [
                    'rgba(0, 0, 0, 0.99)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgb(255, 230, 0)',
                    'rgb(75, 192, 192)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgb(255, 160, 64)'
                ],
                borderColor: [
                    'rgba(0, 0, 0, 0.99)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgb(255, 230, 0)',
                    'rgb(75, 192, 192)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgb(255, 160, 64)'
                ],
                borderWidth: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
