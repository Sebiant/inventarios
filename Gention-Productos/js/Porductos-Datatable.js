$(document).ready(function() {
    var table = $('#datos').DataTable({
        paging: false,
        searching: false,
        info: false,
        "lengthChange": true,
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": "Productos-Controlador.php",
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json"
        },
        "columns": [
            { "data": "id_producto" },
            { "data": "nombre" },
            { "data": "descripcion" },
            { "data": "unidad_medida" },
            { "data": "categoria" },
            { "data": "estado" },
            {
                data: null,
                defaultContent: '<button class="btn btn-primary w-100 btn-modify">Modificar</button>',
                orderable: false
            },
            {
                data: null,
                render: function (data, type, row) {
                    var buttonClass = row.estado === "Activo" ? "btn-danger" : "btn-success";
                    var buttonText = row.estado === "Activo" ? "Inactivar" : "Activar";
                    return `<button class="btn ${buttonClass} w-100 btn-toggle-state">${buttonText}</button>`;
                },
                orderable: false
            }
        ]
    });

    $('#datos tbody').on('click', '.btn-toggle-state', function () {
        var data = table.row($(this).parents('tr')).data();

        var idProducto = data.id_producto;
        var nuevoEstado = data.estado === "Activo" ? 0 : 1;

        console.log("ID Producto:", idProducto);
        console.log("Estado Actual:", data.estado);
        console.log("Nuevo Estado:", nuevoEstado);

        $.ajax({
            url: 'Productos-Controlador.php?accion=cambiarEstado',
            type: 'POST',
            data: { id_producto: idProducto, estado: nuevoEstado },
            success: function () {
                console.log("Estado cambiado con éxito");
                table.ajax.reload();
            },
            error: function () {
                alert("Hubo un error al cambiar el estado del producto.");
            }
        });
    });

    $('#datos tbody').on('click', '.btn-modify', function() {
        var data = table.row($(this).parents('tr')).data();
        var idProducto = data.id_producto;

        console.log("ID Producto:", idProducto);
    
        $.ajax({
            url: 'Productos-Controlador.php?accion=buscarPorId',
            type: 'POST',
            data: { id_producto: idProducto },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                if (response.data && response.data.length > 0) {
                    var producto = response.data[0];
    
                    $('#id_producto_editar').val(producto.id_producto);
                    $('#nombre_editar').val(producto.nombre);
                    $('#descripcion_editar').val(producto.descripcion);
                    $('#unidad_medida_editar').val(producto.unidad_medida);
                    $('#categoria_editar').val(producto.categoria);

                    $('#EditModal').modal('show');
                } else {
                    alert('No se encontraron datos para el producto.');
                }
            },
            error: function() {
                alert('Error al obtener los datos del producto.');
            }
        });
    });    
});
