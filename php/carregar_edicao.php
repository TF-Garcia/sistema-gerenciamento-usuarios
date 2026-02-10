<?php
include_once("conexao.php");

$id = $_GET['id'] ?? 0;

$sql = "SELECT c.*, s.* FROM carros c 
        INNER JOIN servicos s ON c.carro_id = s.carro_id 
        WHERE c.carro_id = $id";
$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $dados = mysqli_fetch_assoc($resultado);
    header('Content-Type: application/json');
    echo json_encode($dados);
} else {
    echo json_encode([]);
}
?>