document.addEventListener("DOMContentLoaded", function () {
    function createChart(canvasId, data, color, label, xLabels, yLabel) {
        const ctx = document.getElementById(canvasId).getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: xLabels, // Utilisation des labels réels pour l'axe X
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: color + "80", // Transparent background for the line
                    borderWidth: 5,
                    fill: false,
                    pointBackgroundColor: color, // Points will have the same color as the line
                    pointRadius: 0, // Make points visible
                    pointHoverRadius: 6, // Larger points on hover
                    tension: 0.4 // Slightly smoother curve
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true, // Show X-axis labels
                        ticks: {
                            beginAtZero: false, // Do not force Y-axis to start from zero
                            stepSize: 5, // Control step size on the Y-axis
                            color: 'black', // Couleur des labels de l'axe Y
                        },
                        grid: {
                            display: true, // Remove X gridlines
                            borderColor: 'black', // Couleur de la ligne de l'axe X (ligne de bordure)
                            borderWidth: 2 // Largeur de la ligne de l'axe X
                        },
                        title: {
                            color:"black",
                            display: true, // Display title on X-axis
                            text: "Time", // X-axis title
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                    },
                    y: {
                        display: true, // Show Y-axis labels
                        ticks: {
                            beginAtZero: false, // Do not force Y-axis to start from zero
                            stepSize: 5, // Control step size on the Y-axis
                            color: 'black', // Couleur des labels de l'axe Y
                        },
                        grid: {
                            // borderColor: "#ddd", // Light color for Y gridlines
                            // borderWidth: 1
                            display:false
                        },
                        title: {
                            color:"black",
                            display: true, // Display title on Y-axis
                            text: yLabel, // Y-axis title
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
            
                    }
                },
                elements: {
                    line: {
                        tension: 0.4 // Smooth out the line
                    },
                    point: {
                        radius: 3,
                        hoverRadius: 8
                    }
                },
                plugins: {

                    legend: {
                        display: false, // Hide legend for a cleaner look
                    },
                    tooltip: {
                        enabled: true, // Show tooltips on hover
                        backgroundColor: "#fff", // White background for tooltip
                        titleColor: "#fff", // Black color for tooltip title
                        bodyColor: "#fff", // Black color for tooltip body
                        borderColor: color, // Border color of the tooltip
                        borderWidth: 2
                    }
                },
                animation: {
                    duration: 1000, // Smooth animation duration
                    easing: 'easeInOutQuad' // Smooth easing effect
                }
            }
        });
    }

    // Exemple de données et labels pour l'axe X (par exemple, jours de la semaine)
    const xLabels = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    
    createChart("chart1", [260, 255, 250, 245, 240, 243], "rgb(255, 99, 132)", "Red Line", xLabels, "");
    createChart("chart2", [45, 46, 47, 46.5, 48, 48.06], "rgb(75, 192, 192)", "Green Line", xLabels, "");
    createChart("chart3", [6.8, 6.75, 6.7, 6.65, 6.6, 6.49], "rgb(54, 162, 235)", "Blue Line", xLabels, "");
});
