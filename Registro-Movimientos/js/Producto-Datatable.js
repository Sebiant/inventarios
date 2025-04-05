let tablaStock;

$(document).ready(function () {

    function cargarProductos() {
        $.ajax({
            url: "Movimientos-Controlador.php?accion=obtenerProductos",
            type: "GET",
            dataType: "json",
            success: function (response) {
                const productos = response.data;
                const $select = $('#id_producto');
                $select.empty().append(`<option selected disabled>Seleccione un producto</option>`);
                productos.forEach(producto => {
                    $select.append(`<option value="${producto.id_producto}">${producto.nombre}</option>`);
                });
            },
            error: function () {
                console.error("Error al cargar productos");
            }
        });
    }
    cargarProductos();

    tablaStock = $('#tablaStockActual').DataTable({
        columnDefs: [{ className: "text-center", targets: "_all" }],
        ajax: {
            url: "Movimientos-Controlador.php?accion=calcularProducto",
            type: "GET",
            dataSrc: "data"
        },
        columns: [
            { data: "producto" },
            { data: "stock_actual" },
            {
                data: "id_producto",
                render: function (data) {
                    return `
                        <button class="btn btn-success btn-sm btn-ingresar" data-id="${data}">
                            <i class="bi bi-plus-circle"></i> Ingresar
                        </button>
                    `;
                }
            },
            {
                data: "id_producto",
                render: function (data) {
                    return `
                        <button class="btn btn-danger btn-sm btn-retirar" data-id="${data}">
                            <i class="bi bi-dash-circle"></i> Retirar
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json"
        }
    });

    $('#tablaStockActual').on('click', '.btn-ingresar', function () {
        const idProducto = $(this).data('id');
        abrirModalMovimiento(idProducto, 'Entrada');
    });

    $('#tablaStockActual').on('click', '.btn-retirar', function () {
        const idProducto = $(this).data('id');
        abrirModalMovimiento(idProducto, 'Salida');
    });

    function abrirModalMovimiento(idProducto, tipoMovimiento) {
        const data = tablaStock.rows().data().toArray();
        const producto = data.find(p => p.id_producto == idProducto);

        if (producto) {
            $('#id_producto').val(producto.id_producto);
            $('#tipo_movimiento').val(tipoMovimiento);

            const tipoTexto = tipoMovimiento === 'Entrada' ? 'Registrar Ingreso' : 'Registrar Retiro';
            const tituloCompleto = `${tipoTexto}: ${producto.producto}`;
            $('#nuevoMovimientoModalLabel').text(tituloCompleto);

            $('#nuevoMovimientoModal').modal('show');
        } else {
            alert('Producto no encontrado');
        }
    }
});

$('#nuevoMovimientoForm').on('submit', function (e) {
    e.preventDefault();
    GuardarMovimiento();
});

function GuardarMovimiento() {
    const formData = new FormData(document.getElementById('nuevoMovimientoForm'));

    console.log('Datos del formulario:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    $.ajax({
        url: 'Movimientos-Controlador.php?accion=registroMovimiento',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert(response.mensaje);
                $('#nuevoMovimientoForm')[0].reset();
                $('#nuevoMovimientoModal').modal('hide');
                tablaStock.ajax.reload();
            } else {
                alert(response.mensaje);
            }
        },
        error: function () { 
            alert("Error al enviar los datos");
        }
    });
}


