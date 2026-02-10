<?php
include_once("conexao.php");

$carro_id = $_GET['carro_id'] ?? 0;

$sql = "SELECT * FROM pecas WHERE carro_id = $carro_id";
$resultado = mysqli_query($conn, $sql);

$pecas = [];
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($peca = mysqli_fetch_assoc($resultado)) {
        $pecas[] = [
            'nome' => $peca['nome'],
            'garantia' => $peca['garantia']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($pecas);
?>