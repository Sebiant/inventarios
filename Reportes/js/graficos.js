let chartEntradasSalidas;
let chartMasMovidos;

function cargarGraficoEntradasSalidas(mesSeleccionado) {
    $.ajax({
        url: 'Reportes-Controlador.php',
        type: 'GET',
        data: {
            accion: 'reportePorMes',
            mes: mesSeleccionado
        },
        dataType: 'json',
        success: function(response) {
            if (!response || !response.data) {
                console.warn("⚠️ Respuesta vacía o malformada para Entradas vs Salidas");
                return;
            }

            const datos = response.data;
            const etiquetas = datos.map(item => item.periodo);
            const entradas = datos.map(item => parseInt(item.total_entradas));
            const salidas = datos.map(item => parseInt(item.total_salidas));

            const canvas = document.getElementById('graficoEntradasSalidas');
            if (!canvas) {
                console.error("🚫 No se encontró el canvas #graficoEntradasSalidas");
                return;
            }

            const ctx = canvas.getContext('2d');
            if (chartEntradasSalidas) chartEntradasSalidas.destroy();

            chartEntradasSalidas = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: etiquetas,
                    datasets: [
                        {
                            label: 'Entradas',
                            data: entradas,
                            backgroundColor: '#4CAF50'
                        },
                        {
                            label: 'Salidas',
                            data: salidas,
                            backgroundColor: '#F44336'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        },
        error: function() {
            alert('❌ Error al obtener los datos del mes seleccionado.');
        }
    });
}

function cargarGraficoMasMovidos() {
    $.ajax({
        url: 'Reportes-Controlador.php',
        type: 'GET',
        data: { accion: 'masMovidos' },
        dataType: 'json',
        success: function(response) {
            if (!response || !Array.isArray(response.data)) {
                console.warn("⚠️ Respuesta inválida para productos más movidos", response);
                return;
            }

            const data = response.data;
            console.log("mas movidos:", data); // ✅ Muestra el texto + el array

            const etiquetas = data.map(item => item.nombre);
            const cantidades = data.map(item => parseInt(item.total_movimiento) || 0);

            const canvas = document.getElementById('graficoMasMovidos');
            if (!canvas) {
                console.error("🚫 No se encontró el canvas #graficoMasMovidos");
                return;
            }

            const ctx = canvas.getContext('2d');
            if (chartMasMovidos) chartMasMovidos.destroy();

            chartMasMovidos = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: etiquetas,
                    datasets: [{
                        label: 'Total Movimientos',
                        data: cantidades,
                        backgroundColor: '#2196F3'
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            suggestedMax: 10
                        }
                    }
                }
            });
        },
        error: function() {
            alert('❌ Error al cargar productos más movidos.');
        }
    });
}

function cargarProductosBajoStock() {
    $.ajax({
        url: 'Reportes-Controlador.php',
        type: 'GET',
        data: { accion: 'stockBajo' },
        dataType: 'json',
        success: function(response) {
            const lista = $('#listaStockBajo');
            if (!lista.length) {
                console.error("🚫 No se encontró el elemento #listaStockBajo");
                return;
            }

            lista.empty();

            if (!response || !Array.isArray(response.data)) {
                console.warn("⚠️ Respuesta inválida para productos con bajo stock", response);
                lista.append('<li class="list-group-item text-warning">No se pudo cargar la lista.</li>');
                return;
            }

            const data = response.data;
            if (data.length === 0) {
                lista.append('<li class="list-group-item">Todo en orden 👍</li>');
            } else {
                data.forEach(producto => {
                    lista.append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${producto.nombre} (${producto.unidad_medida})
                            <span class="badge bg-danger">${producto.stock_actual}</span>
                        </li>
                    `);
                });
            }

        },
        error: function() {
            alert('❌ Error al cargar productos con bajo stock.');
        }
    });
}

$(document).ready(function() {
    const mesInicial = $('#mesSelector').val();
    cargarGraficoEntradasSalidas(mesInicial);
    cargarGraficoMasMovidos();
    cargarProductosBajoStock();

    $('#mesSelector').on('change', function() {
        const mesSeleccionado = $(this).val();
        cargarGraficoEntradasSalidas(mesSeleccionado);
    });
});
