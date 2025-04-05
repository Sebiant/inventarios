<?php
    include_once '../Conexion.php';

    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'default';

    switch ($accion) {
        case 'calcularProducto':
            $sql = "SELECT 
                        p.id_producto,
                        p.nombre AS producto,
                        p.unidad_medida,
                        COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END), 0) -
                        COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END), 0) AS stock_actual
                    FROM productos p
                    LEFT JOIN movimientos m ON p.id_producto = m.id_producto
                    WHERE p.estado = 1
                    GROUP BY p.id_producto, p.nombre, p.unidad_medida";

            $result = $conn->query($sql);

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode(['data' => $data]);
            break;
            
        case 'obtenerProductos':
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

    case 'registroMovimiento':

        $id_producto = $_POST['id_producto'] ?? null;
        $tipo_movimiento = $_POST['tipo_movimiento'] ?? null;
        $cantidad = $_POST['cantidad'] ?? null;
        $fecha = date('Y-m-d H:i:s'); // Ahora

        if (!$id_producto || !$tipo_movimiento || !$cantidad) {
            echo json_encode(['success' => false, 'mensaje' => 'Todos los campos son obligatorios']);
            exit;
        }

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("INSERT INTO movimientos (id_producto, tipo_movimiento, cantidad, fecha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $id_producto, $tipo_movimiento, $cantidad, $fecha);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'mensaje' => 'Movimiento registrado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al registrar el movimiento']);
        }

        $stmt->close();
        $conn->close();
        break;


        default:
            $conn->query("SET lc_time_names = 'es_ES'");

            $sql = "SELECT 
                        m.id_movimiento, 
                        m.id_producto, 
                        p.nombre AS producto, 
                        p.unidad_medida, 
                        m.tipo_movimiento, 
                        m.cantidad, 
                        DATE_FORMAT(m.fecha, '%d de %M de %Y') AS fecha
                    FROM movimientos m
                    JOIN productos p ON p.id_producto = m.id_producto";

            $result = $conn->query($sql);

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            echo json_encode(['data' => $data]);
            break;
    }
?>
