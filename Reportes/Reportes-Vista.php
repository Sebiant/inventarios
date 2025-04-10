<?php 
include_once '../Componentes/header.php'; 
include_once '../Conexion.php';

$opcionesMeses = '';
$sql = "SELECT DISTINCT DATE_FORMAT(fecha, '%Y-%m') as mes FROM movimientos ORDER BY mes DESC";
$result = $conn->query($sql);

$mesesNombres = [
    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
    '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
    '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];

while ($row = $result->fetch_assoc()) {
    $mes = $row['mes'];
    [$anio, $numeroMes] = explode('-', $mes);
    $nombreMes = $mesesNombres[$numeroMes];
    $selected = ($mes === date('Y-m')) ? 'selected' : '';
    $opcionesMeses .= "<option value='$mes' $selected>$nombreMes $anio</option>";
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Productos con Bajo Stock</h5>
        </div>
        <div class="card-body">
            <ul id="listaStockBajo" class="list-group"></ul>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Entradas vs Salidas por Mes</h5>
                    <select id="mesSelector" class="form-select w-auto">
                        <?= $opcionesMeses ?>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="graficoEntradasSalidas"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4 mt-md-0">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Productos Más Movidos</h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoMasMovidos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Enviar Inventario</h5>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6 d-flex flex-wrap gap-2">
                    <input type="text" id="phoneNumber" class="form-control" placeholder="3011234567">
                    <button class="btn btn-primary" id="sendButton">Enviar Mensaje</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once '../Componentes/footer.php'; ?>
<script src="js/msg.js"></script>
<script src="js/graficos.js"></script>
