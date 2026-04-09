document.addEventListener('DOMContentLoaded', function () {

    const meses = [
        'Ene','Feb','Mar','Abr','May','Jun',
        'Jul','Ago','Sep','Oct','Nov','Dic'
    ];

    // 📈 Ingresos por mes
    new Chart(document.getElementById('chartIngresos'), {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Ingresos',
                data: ingresosPorMes,
                tension: 0.3,
                fill: true
            }]
        }
    });

    // 📊 Pedidos por mes
    new Chart(document.getElementById('chartPedidos'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Pedidos',
                data: pedidosPorMes
            }]
        }
    });

    // 🥧 Pedidos por estado
    new Chart(document.getElementById('chartEstados'), {
        type: 'doughnut',
        data: {
            labels: estadosLabels,
            datasets: [{
                data: estadosData
            }]
        }
    });

});
