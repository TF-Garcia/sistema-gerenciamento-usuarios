<?php
include_once("conexao.php");

// Função para gerar ID numérico único de 9 dígitos
function geraIdUnico($conn, $tabela, $campo) {
    do {
        $id = rand(100000000, 999999999);
        $sql = "SELECT 1 FROM $tabela WHERE $campo = $id LIMIT 1";
        $res = mysqli_query($conn, $sql);
    } while ($res && mysqli_num_rows($res) > 0);
    return $id;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
    $dono = mysqli_real_escape_string($conn, $_POST['dono']);
    $placa = mysqli_real_escape_string($conn, $_POST['placa']);
    $modelo = mysqli_real_escape_string($conn, $_POST['modelo']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $valor = floatval($_POST['valor']);
    $data_servico = mysqli_real_escape_string($conn, $_POST['data_servico']);
    $meses_garantia = intval($_POST['meses_garantia']);
    $garantia_servico = mysqli_real_escape_string($conn, $_POST['garantia_servico']);

    // Verifica se o carro já existe pelo CPF e placa
    $sql_verifica = "SELECT carro_id FROM carros WHERE CPF = '$cpf' AND placa = '$placa' LIMIT 1";
    $res_verifica = mysqli_query($conn, $sql_verifica);

    if ($res_verifica && mysqli_num_rows($res_verifica) > 0) {
        $row = mysqli_fetch_assoc($res_verifica);
        $carro_id = intval($row['carro_id']);
    } else {
        $carro_id = geraIdUnico($conn, 'carros', 'carro_id');
        $sql_carro = "INSERT INTO carros (carro_id, CPF, dono, placa, modelo)
                      VALUES ($carro_id, '$cpf', '$dono', '$placa', '$modelo')";
        if (!mysqli_query($conn, $sql_carro)) {
            echo "Erro ao cadastrar o carro: " . mysqli_error($conn);
            exit;
        }
    }

    $servico_id = geraIdUnico($conn, 'servicos', 'servico_id');
    $sql_servico = "INSERT INTO servicos (servico_id, carro_id, descricao, valor, data_servico, meses_garantia, garantia_servico)
                    VALUES ($servico_id, $carro_id, '$descricao', $valor, '$data_servico', $meses_garantia, '$garantia_servico')";

    if (mysqli_query($conn, $sql_servico)) {
        if (!empty($_POST['peca_nome']) && is_array($_POST['peca_nome'])) {
            foreach ($_POST['peca_nome'] as $index => $peca_nome) {
                $nome_peca = trim($peca_nome);
                if ($nome_peca !== '') {
                    $nome_peca = mysqli_real_escape_string($conn, $nome_peca);
                    $garantia_peca = mysqli_real_escape_string($conn, $_POST['peca_garantia'][$index] ?? '');
                    $peca_id = geraIdUnico($conn, 'pecas', 'peca_id');
                    $sql_peca = "INSERT INTO pecas (peca_id, servico_id, nome, garantia_peca)
                                 VALUES ($peca_id, $servico_id, '$nome_peca', '$garantia_peca')";
                    mysqli_query($conn, $sql_peca);
                }
            }
        }
        echo "OK";
    } else {
        echo "Erro ao cadastrar o serviço: " . mysqli_error($conn);
    }

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
} else {
    echo "Método inválido";
}
?>
