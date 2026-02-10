<?php
require("conexao.php");

// Função para gerar ID numérico de 9 dígitos
function geraId() {
    return rand(100000000, 999999999);
}

$garantias = [3, 6, 9];
$dataHoje = date("Y-m-d");

// Inserção de 10 clientes
for ($i = 1; $i <= 10; $i++) {
    // CPF fictício
    $cpf = "12345678" . str_pad($i, 2, "0", STR_PAD_LEFT);
    $dono = "Cliente Exemplo $i";

    // Cada cliente terá 2 carros
    for ($c = 1; $c <= 2; $c++) {
        // Gerar ID para carro
        $carro_id = geraId();
        $placa = "ABC" . str_pad($i.$c, 4, "0", STR_PAD_LEFT);
        $modelo = "Modelo " . chr(64 + $c);

        // Inserir carro
        $sqlCarro = "INSERT INTO carros (carro_id, CPF, dono, placa, modelo)
                     VALUES ($carro_id, '$cpf', '$dono', '$placa', '$modelo')";
        $resCarro = mysqli_query($conn, $sqlCarro);

        if (!$resCarro) {
            echo "Erro ao inserir carro $placa: " . mysqli_error($conn) . "<br>";
            continue;
        }

        // Alternar garantia entre 3, 6 e 9 meses
        $meses = $garantias[(($i + $c) - 2) % 3];
        $dataGarantia = date("Y-m-d", strtotime("+$meses months", strtotime($dataHoje)));

        // Inserir serviço com campo meses_garantia
        $servico_id = geraId();
        $descricao = "Serviço padrão carro $placa";
        $valor = rand(200, 1500);

        $sqlServico = "INSERT INTO servicos 
            (servico_id, carro_id, descricao, valor, data_servico, meses_garantia, garantia_servico)
            VALUES 
            ($servico_id, $carro_id, '$descricao', $valor, '$dataHoje', $meses, '$dataGarantia')";
        $resServico = mysqli_query($conn, $sqlServico);

        if (!$resServico) {
            echo "Erro ao inserir serviço para o carro $placa: " . mysqli_error($conn) . "<br>";
            continue;
        }

        // Inserir 2 peças associadas a este serviço
        for ($p = 1; $p <= 2; $p++) {
            $peca_id = geraId();
            $nomePeca = "Peça $p carro $placa";
            $garantiaPeca = date("Y-m-d", strtotime("+6 months", strtotime($dataHoje)));

            $sqlPeca = "INSERT INTO pecas 
                (peca_id, servico_id, nome, garantia_peca)
                VALUES 
                ($peca_id, $servico_id, '$nomePeca', '$garantiaPeca')";
            $resPeca = mysqli_query($conn, $sqlPeca);

            if (!$resPeca) {
                echo "Erro ao inserir peça $p para o carro $placa: " . mysqli_error($conn) . "<br>";
            }
        }
    }
}

echo "Cadastro concluído com sucesso!";
?>
