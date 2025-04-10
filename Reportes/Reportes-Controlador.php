<?php
header('Content-Type: application/json');
include_once '../Conexion.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'default';

switch ($accion) {

    case 'reportePorMes':
        $mes = isset($_GET['mes']) ? $_GET['mes'] : null;

        if ($mes) {
            $stmt = $conn->prepare("
                SELECT 
                    DATE_FORMAT(m.fecha, '%Y-%m') AS periodo,
                    SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END) AS total_entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END) AS total_salidas
                FROM movimientos m
                WHERE DATE_FORMAT(m.fecha, '%Y-%m') = ?
                GROUP BY periodo
                ORDER BY periodo
            ");
            $stmt->bind_param("s", $mes);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "
                SELECT 
                    DATE_FORMAT(m.fecha, '%Y-%m') AS periodo,
                    SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END) AS total_entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END) AS total_salidas
                FROM movimientos m
                GROUP BY periodo
                ORDER BY periodo
            ";
            $result = $conn->query($sql);
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode(['data' => $data]);
        break;

        case 'masMovidos':
            $sql = "
                SELECT 
                    p.nombre,
                    SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END) AS entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END) AS salidas,
                    (
                        SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END) +
                        SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END)
                    ) AS total_movimiento
                FROM productos p
                LEFT JOIN movimientos m ON p.id_producto = m.id_producto
                GROUP BY p.id_producto, p.nombre
                ORDER BY total_movimiento DESC
                LIMIT 10
            ";
        
            $result = $conn->query($sql);
            $data = [];
        
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        
            echo json_encode(['data' => $data]);
            break;
        

    case 'stockBajo':
        $sql = "
            SELECT 
                p.nombre, 
                p.unidad_medida,
                COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END), 0) AS stock_actual
            FROM productos p
            LEFT JOIN movimientos m ON p.id_producto = m.id_producto
            GROUP BY p.id_producto, p.nombre, p.unidad_medida
            HAVING stock_actual <= 10
            ORDER BY stock_actual ASC
        ";

        $result = $conn->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode(['data' => $data]);
        break;

    case 'consultaMsg':
        $sql = "SELECT 
                    p.nombre, 
                    p.unidad_medida,
                    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'Entrada' THEN m.cantidad ELSE 0 END), 0) - 
                    COALESCE(SUM(CASE WHEN m.tipo_movimiento = 'Salida' THEN m.cantidad ELSE 0 END), 0) AS stock
                FROM productos p
                LEFT JOIN movimientos m ON p.id_producto = m.id_producto
                GROUP BY p.id_producto, p.nombre, p.unidad_medida
        ";

        $result = $conn->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
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
                JOIN productos p ON p.id_producto = m.id_producto
                ORDER BY m.fecha DESC";

        $result = $conn->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode(['data' => $data]);
        break;
}

$conn->close();
