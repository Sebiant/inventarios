<?php
    include_once '../Conexion.php';

    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'default';

    switch ($accion) {
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = $_POST['nombre'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $unidad_medida = $_POST['unidad_medida'] ?? '';
                $categoria = $_POST['categoria'] ?? '';
    
                if (!empty($nombre) && !empty($descripcion) && !empty($unidad_medida) && !empty($categoria)) {
                    $sql = "INSERT INTO productos (nombre, descripcion, unidad_medida, categoria) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $nombre, $descripcion, $unidad_medida, $categoria);
    
                    if ($stmt->execute()) {
                        echo json_encode(['success' => true, 'message' => 'Producto creado exitosamente.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al crear el producto.']);
                    }
                    $stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                }
            }
            break;

        case 'editar':
            // Validar si se recibió un ID de producto
            if (!isset($_POST['id_producto']) || empty($_POST['id_producto'])) {
                echo json_encode(['success' => false, 'message' => 'ID de producto no proporcionado.']);
                exit;
            }

            // Capturar los datos del formulario
            $id_producto = intval($_POST['id_producto']);
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $unidad_medida = trim($_POST['unidad_medida']);
            $categoria = trim($_POST['categoria']);

            // Verificar si el producto existe
            $stmt = $conn->prepare("SELECT id_producto FROM productos WHERE id_producto = ?");
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
                exit;
            }
            $stmt->close();

            // Actualizar el producto
            $sql = "UPDATE productos SET nombre = ?, descripcion = ?, unidad_medida = ?, categoria = ? WHERE id_producto = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nombre, $descripcion, $unidad_medida, $categoria, $id_producto);
            $success = $stmt->execute();

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto.']);
            }

            $stmt->close();
            break;

        case 'cambiarEstado':
            $id_producto = $_POST['id_producto'];
            $estado = $_POST['estado'];
    
            $sql = "UPDATE productos SET estado=? WHERE id_producto=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $estado, $id_producto);
    
            if (!$stmt->execute()) {
                echo "Error al cambiar el estado: " . $stmt->error;
            }
            $stmt->close();
            break;

        case 'buscarPorId':
            if (empty($_POST['id_producto'])) {
                echo json_encode(["error" => "id_producto no proporcionado"]);
                exit;
            }
            $sql = "SELECT * FROM productos WHERE id_producto=?";
            $stmt = $conn->prepare($sql);
    
            if (!$stmt) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
    
            $stmt->bind_param('s', $_POST['id_producto']);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                echo json_encode(['data' => $result->fetch_all(MYSQLI_ASSOC)]);
            } else {
                echo json_encode(['error' => 'Registro no encontrado']);
            }
            $stmt->close();
            break;

        default:
            $sql = "SELECT * FROM productos
            ORDER BY estado DESC";

            $result = $conn->query($sql);
        
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $row['estado'] = ($row['estado'] == 1) ? "Activo" : "Inactivo";
                $data[] = $row;
            }
        
            echo json_encode(['data' => $data]);
            break;
    }
?>