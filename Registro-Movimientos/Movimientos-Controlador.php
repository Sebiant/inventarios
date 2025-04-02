<?php
    include_once '../Conexion.php';

    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'default';

    switch ($accion) {
        default:
            $sql = "SELECT * FROM movimientos";

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