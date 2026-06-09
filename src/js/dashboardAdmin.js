new Chart(document.getElementById("chartIngresos"), {
    type: "bar",
    data: {
        labels: [
            "Ene","Feb","Mar","Abr",
            "May","Jun","Jul","Ago",
            "Sep","Oct","Nov","Dic"
        ],
        datasets: [{
            label: "Ingresos",
            data: ingresosPorMes
        }]
    }   
});


new Chart(document.getElementById("chartClientes"), {
    type: "line",
    data: {
        labels: [
            "Ene","Feb","Mar","Abr",
            "May","Jun","Jul","Ago",
            "Sep","Oct","Nov","Dic"
        ],
        datasets: [{
            label: "Clientes nuevos",
            data: nuevosClientesPorMes
        }]
    }
});