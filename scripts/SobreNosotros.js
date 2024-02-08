document.addEventListener('DOMContentLoaded', function () {
 
    const datosVentas = {
        labels: ['2020', '2021', '2022', '2023'],
        datasets: [
            {
                label: 'Ventas por Año',
                data: [15000, 22000, 18000, 25000],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            },
        ],
    };
 
   
    const opcionesGrafico = {
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    };
 
   
    const contexto = document.querySelector('canvas').getContext('2d');
 
   
    const graficoVentas = new Chart(contexto, {
        type: 'bar',
        data: datosVentas,
        options: opcionesGrafico,
    });
});
