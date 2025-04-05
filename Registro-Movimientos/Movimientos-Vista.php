<?php
    include_once '../Componentes/header.php';
?>
<div class="container mt-4">
    <div class="row g-4">
        <!-- Stock Actual -->
        <div class="col-12 col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Registro de Movimientos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaStockActual" class="table table-bordered table-striped mb-0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Ingresar</th>
                                    <th>Retirar</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Movimientos -->
        <div class="col-12 col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Historial de Movimientos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaHistorialMovimientos" class="table table-bordered table-striped mb-0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Unidad de Medida</th>
                                    <th>Producto</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para nuevo movimiento -->
<div class="modal fade" id="nuevoMovimientoModal" tabindex="-1" aria-labelledby="nuevoMovimientoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="nuevoMovimientoForm"> <!-- Mover el form aquí -->
        <div class="modal-header">
          <h5 class="modal-title" id="nuevoMovimientoModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="id_producto" class="form-label">Producto</label>
            <select class="form-select" id="id_producto" name="id_producto">
              <option selected disabled>Seleccione un producto</option>
            </select>
          </div>

          <input type="hidden" id="tipo_movimiento" name="tipo_movimiento">

          <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php
    include_once '../Componentes/footer.php';
?>
<script src="js/Movimientos-Datatable.js"></script>
<script src="js/Producto-Datatable.js"></script>