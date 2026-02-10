<?php
include_once("conexao.php");

$id = $_GET['id'] ?? '';

// Consulta para obter os detalhes do carro e serviços
$sql = "SELECT c.*, s.* 
        FROM carros c 
        INNER JOIN servicos s ON c.carro_id = s.carro_id 
        WHERE c.carro_id = '$id'";

$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $dados = mysqli_fetch_assoc($resultado);
    
    // Função para mascarar o CPF (mostrar apenas primeiros 3 e últimos 2 dígitos)
    function mascararCPF($cpf) {
        if (empty($cpf) || strlen($cpf) < 11) {
            return 'Não informado';
        }
        
        $primeirosTres = substr($cpf, 0, 3);
        $ultimosDois = substr($cpf, -2);
        
        return $primeirosTres . '******' . $ultimosDois;
    }
    
    // Verifica se o campo CPF existe
    $cpf = isset($dados['CPF']) ? $dados['CPF'] : (isset($dados['cpf']) ? $dados['cpf'] : '');
    $cpfMascarado = mascararCPF($cpf);
    
    // Consulta para obter as peças do serviço específico
    $servico_id = $dados['servico_id'] ?? '';
    $sql_pecas = "SELECT * FROM pecas WHERE servico_id = '$servico_id'";
    $resultado_pecas = mysqli_query($conn, $sql_pecas);
    $pecas = [];
    if ($resultado_pecas && mysqli_num_rows($resultado_pecas) > 0) {
        while ($peca = mysqli_fetch_assoc($resultado_pecas)) {
            $pecas[] = $peca;
        }
    }
    ?>
    
    <h2>Detalhes do Cliente</h2>
    
    <h3>Dados do Cliente</h3>
    <p><strong>CPF:</strong> <?php echo htmlspecialchars($cpfMascarado); ?></p>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($dados['dono']); ?></p>
    
    <h3>Dados do Veículo</h3>
    <p><strong>Placa:</strong> <?php echo htmlspecialchars($dados['placa']); ?></p>
    <p><strong>Modelo:</strong> <?php echo htmlspecialchars($dados['modelo']); ?></p>
    
    <h3>Serviço Realizado</h3>
    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($dados['descricao']); ?></p>
    <p><strong>Valor:</strong> R$ <?php echo number_format($dados['valor'], 2, ',', '.'); ?></p>
    <p><strong>Data do Serviço:</strong> <?php echo date('d/m/Y', strtotime($dados['data_servico'])); ?></p>
    <p><strong>Garantia:</strong> <?php echo $dados['meses_garantia']; ?> meses</p>
    <p><strong>Data Final da Garantia:</strong> <?php echo date('d/m/Y', strtotime($dados['garantia_servico'])); ?></p>
    
    <h3>Peças Trocadas</h3>
    <?php if (!empty($pecas)): ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nome da Peça</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Garantia da Peça</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pecas as $peca): ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($peca['nome']); ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            <?php echo $peca['garantia_peca'] ? date('d/m/Y', strtotime($peca['garantia_peca'])) : 'Não especificado'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma peça trocada registrada.</p>
    <?php endif; ?>
    
    <button type="button" onclick="editarCliente('<?php echo $dados['carro_id']; ?>')" style="padding: 10px 15px; background: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px;">
        Editar Dados
    </button>

    <?php
} else {
    echo "<p>Cliente não encontrado.</p>";
}

mysqli_close($conn);
?>
