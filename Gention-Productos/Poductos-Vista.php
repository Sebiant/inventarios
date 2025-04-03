<?php
    include_once '../Componentes/header.php';
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-2 offset-10">
            <div class="text-center">
                <!-- Botón para crear nuevo docente -->
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#CreateModal" id="botonCrear">
                    <i class="bi bi-plus-circle"></i> Crear
                </button>
            </div>
        </div>
    </div>

    <br />
    <div class="card">
        <div class="card-header">
            <h5>Productos</h5>
        </div>
        <div class="table-responsive card-body">
            <table id="datos" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Unidad de Medida</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Modificar</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>        
    </div>
</div>

<!-- Modal modificar -->
<div id="EditModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id_producto" id="id_producto_editar">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre" id="nombre_editar" class="form-control" placeholder="Nombre del producto" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion_editar" class="form-control" rows="3" placeholder="Descripción del producto" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="unidad_medida" class="form-label">Unidad de Medida:</label>
                        <input type="text" name="unidad_medida" id="unidad_medida_editar" class="form-control" placeholder="Unidad de medida">
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría:</label>
                        <input type="text" name="categoria" id="categoria_editar" class="form-control" placeholder="Categoría del producto" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="EditarProducto()">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal crear -->
<div id="CreateModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre" id="nombre_editar" class="form-control" placeholder="Nombre del producto" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion_editar" class="form-control" rows="3" placeholder="Descripción del producto" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="unidad_medida" class="form-label">Unidad de Medida:</label>
                        <input type="text" name="unidad_medida" id="unidad_medida_editar" class="form-control" placeholder="Unidad de medida">
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría:</label>
                        <input type="text" name="categoria" id="categoria_editar" class="form-control" placeholder="Categoría del producto" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="CrearProducto()">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    include_once '../Componentes/footer.php';
?>
<script src="js/Porductos-Datatable.js"></script>
<script>
    function CrearProducto() {
        const formData = new FormData(document.getElementById('createForm'));
        console.log('Datos del formulario:', ...formData.entries());

        $.ajax({
            url: 'Productos-Controlador.php?accion=crear',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                $('#CreateModal').modal('hide');
                $('#datos').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', error);
                alert('Hubo un error al crear el producto.');
            }
        });
    }
</script>
<script>
    function EditarProducto() {
        const formData = new FormData(document.getElementById('editForm'));
        console.log('Datos del formulario:', ...formData.entries());

        $.ajax({
            url: 'Productos-Controlador.php?accion=editar',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                
                try {
                    let res = JSON.parse(response);
                    if (res.success) {
                        $('#EditModal').modal('hide');
                        $('#datos').DataTable().ajax.reload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                } catch (e) {
                    alert('Error inesperado al editar el producto.');
                    console.error('Error al procesar la respuesta:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', error);
                alert('Hubo un error al editar el producto.');
            }
        });
    }
</script>




