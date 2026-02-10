<?php
include_once("conexao.php");

// Captura os dados do formulário, se houver
$termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';
$garantia = isset($_GET['garantia']) ? intval($_GET['garantia']) : 3;

// Consulta SQL
$sql = "SELECT c.carro_id, c.dono, c.placa, c.modelo
        FROM carros c
        INNER JOIN servicos s ON c.carro_id = s.carro_id
        WHERE s.meses_garantia = ? AND c.dono LIKE ?
        ORDER BY c.dono ASC";

$stmt = mysqli_prepare($conn, $sql);
$termo_param = '%' . $termo . '%';
mysqli_stmt_bind_param($stmt, "is", $garantia, $termo_param);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Garantia de Serviços</title>
    <link rel="stylesheet" href="../styles/busca_ajax.css"> <!-- CSS externo -->
</head>
<body>
    <div class="container">
        <h2>Garantia de Serviços</h2>

        <form method="GET" action="">
            <input type="text" name="termo" placeholder="Buscar por nome do cliente" value="<?= htmlspecialchars($termo) ?>" />
            <select name="garantia">
                <option value="3" <?= ($garantia == 3) ? 'selected' : '' ?>>3 meses</option>
                <option value="6" <?= ($garantia == 6) ? 'selected' : '' ?>>6 meses</option>
                <option value="9" <?= ($garantia == 9) ? 'selected' : '' ?>>12 meses</option>
            </select>
            <button type="submit">Buscar</button>
        </form>

        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
            <?php while ($linha = mysqli_fetch_assoc($resultado)): ?>
                <div class="card" onclick="abrirDetalhes(<?= $linha['carro_id'] ?>)">
                    <strong>Dono:</strong> <?= htmlspecialchars($linha['dono']) ?>
                    <div>Placa: <?= htmlspecialchars($linha['placa']) ?></div>
                    <div>Modelo: <?= htmlspecialchars($linha['modelo']) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="sem-resultados">Nenhum resultado encontrado.</p>
        <?php endif; ?>

        <div class="botoes">
            <a href="cadastro.php">Cadastrar novo veículo</a>
            <a href="menu.php">Voltar ao menu</a>
        </div>
    </div>

    <script>
        function abrirDetalhes(carro_id) {
            window.location.href = 'detalhes.php?carro_id=' + carro_id;
        }
    </script>
</body>
</html>
