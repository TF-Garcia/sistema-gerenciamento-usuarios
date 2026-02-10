<?php
include_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    // Processar edição dos dados
    $carro_id = $_POST['carro_id'];
    $cpf = $_POST['cpf'];
    $dono = $_POST['dono'];
    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data_servico = $_POST['data_servico'];
    $meses_garantia = $_POST['meses_garantia'];
    $garantia_servico = $_POST['garantia_servico'];

    // Atualizar carros - usando CPF em maiúsculas
    $sql_carro = "UPDATE carros SET CPF = '$cpf', dono = '$dono', placa = '$placa', modelo = '$modelo' WHERE carro_id = $carro_id";
    mysqli_query($conn, $sql_carro);

    // Atualizar serviços
    $sql_servico = "UPDATE servicos SET descricao = '$descricao', valor = $valor, data_servico = '$data_servico', meses_garantia = $meses_garantia, garantia_servico = '$garantia_servico' WHERE carro_id = $carro_id";
    mysqli_query($conn, $sql_servico);

    // Processar peças trocadas
    if (isset($_POST['peca_nome'])) {
        // Primeiro, remove peças existentes
        $sql_delete_pecas = "DELETE FROM pecas WHERE carro_id = $carro_id";
        mysqli_query($conn, $sql_delete_pecas);
        
        // Insere as novas peças
        foreach ($_POST['peca_nome'] as $index => $nome) {
            $nome_peca = mysqli_real_escape_string($conn, $nome);
            $garantia_peca = isset($_POST['peca_garantia'][$index]) ? $_POST['peca_garantia'][$index] : null;
            
            if (!empty($nome_peca)) {
                $sql_peca = "INSERT INTO pecas (carro_id, nome, garantia) VALUES ($carro_id, '$nome_peca', " . ($garantia_peca ? "'$garantia_peca'" : "NULL") . ")";
                mysqli_query($conn, $sql_peca);
            }
        }
    }
    
    echo "OK";
} else {
    echo "Erro: Método não permitido";
}
?>