let tablaMovimientos;

$(document).ready(function() {
    tablaMovimientos = $('#tablaHistorialMovimientos').DataTable({
        columnDefs: [{ className: "text-center", targets: "_all" }],
        ajax: {
            url: "Movimientos-Controlador.php",
            type: "GET",
            dataSrc: "data"
        },
        columns: [
            { data: "tipo_movimiento" },
            { data: "cantidad" },
            { data: "unidad_medida" },
            { data: "producto" },
            { data: "fecha" }
        ],
        order: [[4, 'desc']]
    });
});
