$(document).ready(function() {
    var table = $('#movimientosTable').DataTable({
        paging: false,
        searching: false,
        info: false,
        lengthChange: true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        ajax: "Movimientos-Controlador.php", // Archivo del servidor que retorna los datos
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json"
        },
        columns: [
            { data: "id_producto" },
            { data: "producto" },
            { data: "total_entradas" },
            { data: "total_salidas" },
            { data: "stock_actual" },
            {
                data: null,
                defaultContent: '<button class="btn btn-success w-100 btn-restock">Restock</button>',
                orderable: false
            },
            {
                data: null,
                defaultContent: '<button class="btn btn-danger w-100 btn-salida">Salida</button>',
                orderable: false
            }
        ]
    });

    // Evento para el botón de "Restock"
    $('#movimientosTable tbody').on('click', '.btn-restock', function () {
        var data = table.row($(this).parents('tr')).data();
        var idProducto = data.id_producto;
        console.log("Restock - ID Producto:", idProducto);
        // Aquí podrías abrir el modal de restock y pasar el idProducto
        // Ejemplo: openRestockModal(idProducto);
    });

    // Evento para el botón de "Salida"
    $('#movimientosTable tbody').on('click', '.btn-salida', function () {
        var data = table.row($(this).parents('tr')).data();
        var idProducto = data.id_producto;
        console.log("Salida - ID Producto:", idProducto);
        // Aquí podrías abrir el modal de salida y pasar el idProducto
        // Ejemplo: openSalidaModal(idProducto);
    });
});
