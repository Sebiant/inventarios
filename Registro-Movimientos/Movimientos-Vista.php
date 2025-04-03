<?php
    include_once '../Componentes/header.php';
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>Gestión de Inventario</h5>
        </div>
        <div class="card-body">
            <!-- Fila con las dos columnas (Stock Actual e Historial de Movimientos) -->
            <div class="row">
                <!-- Columna izquierda: Stock Actual -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5>Stock Actual</h5>
                        </div>
                        <div class="table-responsive card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Stock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí van los datos dinámicos -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Historial de Movimientos -->
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5>Historial de Movimientos</h5>
                        </div>
                        <div class="table-responsive card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí van los datos dinámicos -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- Fin row -->
        </div> <!-- Fin card-body -->
    </div> <!-- Fin card general -->
</div> <!-- Fin container -->

<!-- Modal para editar stock (modificar el stock actual) -->
<div class="modal fade" id="editarStockModal" tabindex="-1" aria-labelledby="editarStockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarStockModalLabel">Modificar Stock Actual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarStock">
          <!-- Campo oculto para el ID del producto -->
          <input type="hidden" id="id_producto_modificar" name="id_producto_modificar">
          <div class="mb-3">
            <label for="producto_modificar" class="form-label">Producto</label>
            <input type="text" class="form-control" id="producto_modificar" name="producto_modificar" readonly>
          </div>
          <div class="mb-3">
            <label for="stock_actual" class="form-label">Stock Actual</label>
            <input type="number" class="form-control" id="stock_actual" name="stock_actual" readonly>
          </div>
          <div class="mb-3">
            <label for="nuevo_stock" class="form-label">Nuevo Stock</label>
            <input type="number" class="form-control" id="nuevo_stock" name="nuevo_stock" min="0" required>
          </div>
          <div class="mb-3">
            <label for="motivo_modificar" class="form-label">Motivo del Ajuste</label>
            <textarea class="form-control" id="motivo_modificar" name="motivo_modificar" rows="2" placeholder="Ej. Ajuste de inventario, error de conteo"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="actualizarStock()">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para nuevo movimiento (se mantiene el anterior, si se necesita) -->
<div class="modal fade" id="nuevoMovimientoModal" tabindex="-1" aria-labelledby="nuevoMovimientoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevoMovimientoModalLabel">Registrar Movimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="nuevoMovimientoForm">
          <div class="mb-3">
            <label for="producto_nuevo" class="form-label">Producto</label>
            <select class="form-select" id="producto_nuevo">
              <option selected disabled>Seleccione un producto</option>
              <option value="1">Laptop</option>
              <option value="2">Teclado</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tipo_nuevo" class="form-label">Tipo de Movimiento</label>
            <select class="form-select" id="tipo_nuevo">
              <option selected disabled>Seleccione un tipo</option>
              <option value="Entrada">Entrada</option>
              <option value="Salida">Salida</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="cantidad_nuevo" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad_nuevo" min="1" required>
          </div>
          <div class="mb-3">
            <label for="motivo_nuevo" class="form-label">Motivo (opcional)</label>
            <textarea class="form-control" id="motivo_nuevo" rows="2"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php
    include_once '../Componentes/footer.php';
?>
<script>
  // Función para cargar datos del stock en el modal de modificar stock
  function cargarStock(producto, stockActual, idProducto) {
    document.getElementById('producto_modificar').value = producto;
    document.getElementById('stock_actual').value = stockActual;
    document.getElementById('nuevo_stock').value = stockActual; // Inicialmente igual al actual
    document.getElementById('id_producto_modificar').value = idProducto;
  }

  // Función para actualizar el stock y generar el movimiento
  function actualizarStock() {
    // Obtén los valores del formulario
    var idProducto = document.getElementById('id_producto_modificar').value;
    var producto = document.getElementById('producto_modificar').value;
    var stockActual = parseInt(document.getElementById('stock_actual').value);
    var nuevoStock = parseInt(document.getElementById('nuevo_stock').value);
    var motivo = document.getElementById('motivo_modificar').value;
    
    // Calcula la diferencia
    var diferencia = nuevoStock - stockActual;
    
    // Determina el tipo de movimiento basado en la diferencia
    var tipoMovimiento = diferencia >= 0 ? "Entrada" : "Salida";
    var cantidadMovimiento = Math.abs(diferencia);
    
    // Aquí implementarías la lógica AJAX para:
    // 1. Actualizar el stock en la base de datos.
    // 2. Registrar el movimiento en la tabla "movimientos".
    console.log("ID Producto:", idProducto);
    console.log("Producto:", producto);
    console.log("Stock Actual:", stockActual);
    console.log("Nuevo Stock:", nuevoStock);
    console.log("Diferencia:", diferencia);
    console.log("Tipo de Movimiento:", tipoMovimiento);
    console.log("Cantidad de Movimiento:", cantidadMovimiento);
    console.log("Motivo:", motivo);
    
    // Ejemplo AJAX (ajusta la URL y parámetros según tu backend):
    /*
    $.ajax({
      url: 'ActualizarStock-Controlador.php',
      type: 'POST',
      data: {
        id_producto: idProducto,
        tipo_movimiento: tipoMovimiento,
        cantidad: cantidadMovimiento,
        motivo: motivo
      },
      success: function(response) {
        console.log("Stock actualizado y movimiento registrado");
        // Aquí podrías actualizar la interfaz, por ejemplo:
        // Actualizar el valor de stock en la tabla "Stock Actual"
        if(producto === "Laptop") {
          document.getElementById('stock-laptop').innerText = nuevoStock;
        } else if(producto === "Teclado") {
          document.getElementById('stock-teclado').innerText = nuevoStock;
        }
      },
      error: function() {
        alert("Error al actualizar el stock");
      }
    });
    */
    
    // Cerrar el modal
    var modal = bootstrap.Modal.getInstance(document.getElementById('editarStockModal'));
    modal.hide();
  }
</script>
