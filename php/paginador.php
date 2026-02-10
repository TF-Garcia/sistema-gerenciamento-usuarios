<?php 
// Configurações do Banco de Dados
require("conexao.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$limiteRegistros = isset($_GET['limite']) ? (int)$_GET['limite'] : 5;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaAtual - 1) * $limiteRegistros;

// Contagem total de registros
$queryTotal = "SELECT COUNT(*) as total FROM carros";
$resultTodos = mysqli_query($conn, $queryTotal);
$totalRegistros = mysqli_fetch_assoc($resultTodos)['total'];
$totalPaginas = ceil($totalRegistros / $limiteRegistros);

// Seleção paginada com data do serviço mais recente
$query = "
    SELECT c.carro_id, 
           c.CPF, 
           c.dono, 
           c.placa, 
           c.modelo,
           MAX(s.data_servico) AS ultimo_servico
    FROM carros c
    LEFT JOIN servicos s ON c.carro_id = s.carro_id
    GROUP BY c.carro_id, c.CPF, c.dono, c.placa, c.modelo
    LIMIT $limiteRegistros OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Processamento de exclusão múltipla por seleção manual
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete']) && $_POST['delete'] == 'excluir') {
        if (isset($_POST['selecionados'])) {
            foreach ($_POST['selecionados'] as $selecionado) {
                list($carro_id, $CPF) = explode('|', $selecionado);

                $queryDeletePecas = "
                    DELETE p FROM pecas p
                    JOIN servicos s ON p.servico_id = s.servico_id
                    WHERE s.carro_id = '$carro_id'
                ";
                mysqli_query($conn, $queryDeletePecas);

                $queryDeleteServicos = "DELETE FROM servicos WHERE carro_id = '$carro_id'";
                mysqli_query($conn, $queryDeleteServicos);

                $queryDeleteCarro = "DELETE FROM carros WHERE carro_id = '$carro_id' AND CPF = '$CPF'";
                mysqli_query($conn, $queryDeleteCarro);
            }
            header("Location: ?pagina=$paginaAtual&limite=$limiteRegistros");
            exit;
        }
    }

    // Processamento de exclusão múltipla por data
    if (isset($_POST['delete']) && $_POST['delete'] == 'excluir_data') {
        $quantidade = (int)$_POST['quantidade'];
        $ordem = $_POST['ordem'] === 'asc' ? 'ASC' : 'DESC';

        // Seleciona os carros a excluir
        $sqlCarros = "
            SELECT c.carro_id, c.CPF
            FROM carros c
            LEFT JOIN servicos s ON c.carro_id = s.carro_id
            GROUP BY c.carro_id, c.CPF
            ORDER BY MAX(s.data_servico) $ordem
            LIMIT $quantidade
        ";
        $resCarros = mysqli_query($conn, $sqlCarros);

        while ($row = mysqli_fetch_assoc($resCarros)) {
            $carro_id = $row['carro_id'];
            $CPF = $row['CPF'];

            $queryDeletePecas = "
                DELETE p FROM pecas p
                JOIN servicos s ON p.servico_id = s.servico_id
                WHERE s.carro_id = '$carro_id'
            ";
            mysqli_query($conn, $queryDeletePecas);

            $queryDeleteServicos = "DELETE FROM servicos WHERE carro_id = '$carro_id'";
            mysqli_query($conn, $queryDeleteServicos);

            $queryDeleteCarro = "DELETE FROM carros WHERE carro_id = '$carro_id' AND CPF = '$CPF'";
            mysqli_query($conn, $queryDeleteCarro);
        }

        header("Location: ?pagina=1&limite=$limiteRegistros");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Carros</title>
    <link rel="stylesheet" href="../styles/paginador.css">
</head>
<body>
    <header>
        <a href="indice.php">Página Inicial</a>
        <a href="#" onclick="abrirModalCadastro()">Cadastrar Carros</a>
        <div class="dropdown">
            <button class="dropbtn" onclick="toggleDropdown('sistemaDropdown')">Gerenciar Sistema</button>
            <div id="sistemaDropdown" class="dropdown-content">
                <a href="senha.php">Definir/Trocar Senha</a>
                <a href="#" onclick="abrirModalExclusao()">Excluir Múltiplos</a>
            </div>
        </div>
    </header>

    <div id="main">
        <h1>Gerenciamento de Carros</h1>

        <form method="get" action="">
            <label for="limite">Qtde. registros por página:</label>
            <select id="limite" name="limite" onchange="this.form.submit()">
                <option value="5" <?php if($limiteRegistros == 5) echo "selected"; ?>>5</option>
                <option value="10" <?php if($limiteRegistros == 10) echo "selected"; ?>>10</option>
                <option value="15" <?php if($limiteRegistros == 15) echo "selected"; ?>>15</option>
            </select>
        </form>
        <br>

        <form method="post" action="">
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Excluir</th>
                        <th>Editar</th>
                        <th>Carro ID</th>
                        <th>CPF</th>
                        <th>Dono</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Último Serviço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $chaveComposta = $row['carro_id'] . '|' . $row['CPF'];
                            echo "<tr>";
                            echo "<td><input type='checkbox' name='selecionados[]' value='$chaveComposta'></td>";
                            echo "<td><input type='radio' name='selecionado' value='$row[carro_id]'></td>";
                            echo "<td>" . htmlspecialchars($row['carro_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['CPF']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dono']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['placa']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['modelo']) . "</td>";
                            echo "<td>" . ($row['ultimo_servico'] ? htmlspecialchars($row['ultimo_servico']) : '---') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Nenhum dado encontrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="actions">
                <button type="submit" name="delete" value="excluir">Excluir Selecionados</button>
                <button type="button" onclick="editarSelecionado()">Editar</button>
            </div>
        </form>

        <div class="paginacao">
            <?php
            for ($pagina = 1; $pagina <= $totalPaginas; $pagina++) {
                $classeAtiva = $paginaAtual == $pagina ? 'active' : '';
                echo "<a href='?pagina=$pagina&limite=$limiteRegistros' class='$classeAtiva'>$pagina</a> ";
            }
            ?>
        </div>
    </div>

    <!-- Modal Exclusão por Data -->
    <div class="modal" id="modalExclusao">
        <div class="modal-content">
            <span class="close-modal" onclick="fecharModalExclusao()">&times;</span>
            <h2>Excluir Múltiplos Clientes por Data</h2>
            <form method="post" action="">
                <label>Quantidade:</label>
                <select name="quantidade">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <br><br>
                <label>Ordem:</label>
                <select name="ordem">
                    <option value="desc">Mais novos primeiro</option>
                    <option value="asc">Mais antigos primeiro</option>
                </select>
                <br><br>
                <button type="submit" name="delete" value="excluir_data">Excluir</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModalExclusao() {
            document.getElementById('modalExclusao').style.display = 'flex';
        }

        function fecharModalExclusao() {
            document.getElementById('modalExclusao').style.display = 'none';
        }

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('modalExclusao')) fecharModalExclusao();
        });

        function editarSelecionado() {
            const selecionado = document.querySelector('input[name="selecionado"]:checked');
            if (!selecionado) {
                alert("Selecione um cliente para editar.");
                return;
            }
            carregarDadosEdicao(selecionado.value);
        }

        function toggleDropdown(id) {
            document.querySelectorAll('.dropdown-content').forEach(dc => {
                if (dc.id !== id) {
                    dc.style.display = 'none';
                }
            });
            const dropdown = document.getElementById(id);
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('modalCadastro')) fecharModalCadastro();
            if (event.target === document.getElementById('modalDetalhes')) fecharModalDetalhes();
            if (event.target === document.getElementById('modalEdicao')) fecharModalEdicao();
            if (!event.target.matches('.dropbtn')) {
                document.querySelectorAll('.dropdown-content').forEach(dc => dc.style.display = 'none');
            }
        });

        function abrirModalCadastro() {
            function geraId() {
                return Math.floor(100000000 + Math.random() * 900000000);
            }
            document.getElementById('carro_id').value = geraId();
            document.getElementById('formCadastro').reset();
            document.getElementById('garantia_servico').value = '';
            document.getElementById('modalCadastro').style.display = 'flex';
        }
        
        function fecharModalCadastro() {
            document.getElementById('modalCadastro').style.display = 'none';
        }

        function adicionarPeca() {
            const container = document.getElementById('pecas-container');
            const div = document.createElement('div');
            div.innerHTML = `
                <label>Nome da Peça:</label>
                <input type="text" name="peca_nome[]">
                <label>Garantia da Peça:</label>
                <input type="date" name="peca_garantia[]">
            `;
            container.appendChild(div);
        }
        
        function calcularGarantia() {
            const dataServico = document.getElementById('data_servico').value;
            const meses = parseInt(document.getElementById('meses_garantia').value);
        
            if (!dataServico || !meses) {
                alert('Preencha a data do serviço e a garantia.');
                return false;
            }
        
            const data = new Date(dataServico);
            data.setMonth(data.getMonth() + meses);
            const garantiaFormatada = data.toISOString().split('T')[0];
            document.getElementById('garantia_servico').value = garantiaFormatada;
            return true;
        }
        
        // Evento submit do formulário de cadastro
        document.getElementById('formCadastro').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!calcularGarantia()) return;
        
            const form = e.target;
            const formData = new FormData(form);
        
            fetch('cadastro.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.text())
            .then(texto => {
                if (texto.trim() === 'OK') {
                    alert('Cadastro realizado com sucesso!');
                    form.reset();
                    fecharModalCadastro();
                    location.reload();
                } else {
                    alert('Erro: ' + texto);
                }
            })
            .catch(() => alert('Erro na comunicação com o servidor.'));
        });

        // ---- Edição ----
        function editarCliente(id) {
            fecharModalDetalhes();
            carregarDadosEdicao(id);
        }
        
        function carregarDadosEdicao(id) {
            fetch('carregar_edicao.php?id=' + id)
                .then(response => response.json())
                .then(dados => {
                    document.getElementById('editar_carro_id').value = dados.carro_id;
                    document.getElementById('editar_cpf').value = dados.CPF || '';
                    document.getElementById('editar_dono').value = dados.dono;
                    document.getElementById('editar_placa').value = dados.placa;
                    document.getElementById('editar_modelo').value = dados.modelo || '';
                    document.getElementById('editar_descricao').value = dados.descricao;
                    document.getElementById('editar_valor').value = dados.valor;
                    document.getElementById('editar_data_servico').value = dados.data_servico;
                    document.getElementById('editar_meses_garantia').value = dados.meses_garantia;
                    
                    carregarPecasEdicao(id);
                    document.getElementById('modalEdicao').style.display = 'flex';
                })
                .catch(() => alert('Erro ao carregar dados para edição.'));
        }
        
        function carregarPecasEdicao(carroId) {
            fetch('carregar_pecas.php?carro_id=' + carroId)
                .then(response => response.json())
                .then(pecas => {
                    const container = document.getElementById('editar-pecas-container');
                    container.innerHTML = '';
                    
                    if (pecas.length === 0) {
                        const div = document.createElement('div');
                        div.innerHTML = `
                            <label>Nome da Peça:</label>
                            <input type="text" name="peca_nome[]">
                            <label>Garantia da Peça:</label>
                            <input type="date" name="peca_garantia[]">
                        `;
                        container.appendChild(div);
                    } else {
                        pecas.forEach(peca => {
                            const div = document.createElement('div');
                            div.innerHTML = `
                                <label>Nome da Peça:</label>
                                <input type="text" name="peca_nome[]" value="${peca.nome || ''}">
                                <label>Garantia da Peça:</label>
                                <input type="date" name="peca_garantia[]" value="${peca.garantia || ''}">
                            `;
                            container.appendChild(div);
                        });
                    }
                })
                .catch(() => {
                    console.log('Erro ao carregar peças');
                    const container = document.getElementById('editar-pecas-container');
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <label>Nome da Peça:</label>
                        <input type="text" name="peca_nome[]">
                        <label>Garantia da Peça:</label>
                        <input type="date" name="peca_garantia[]">
                    `;
                    container.appendChild(div);
                });
        }
        
        function adicionarPecaEdicao() {
            const container = document.getElementById('editar-pecas-container');
            const div = document.createElement('div');
            div.innerHTML = `
                <label>Nome da Peça:</label>
                <input type="text" name="peca_nome[]">
                <label>Garantia da Peça:</label>
                <input type="date" name="peca_garantia[]">
            `;
            container.appendChild(div);
        }
        
        function fecharModalEdicao() {
            document.getElementById('modalEdicao').style.display = 'none';
        }
        
        document.getElementById('formEdicao').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!calcularGarantiaEdicao()) return;
        
            const form = e.target;
            const formData = new FormData(form);
        
            fetch('editar.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.text())
            .then(texto => {
                if (texto.trim() === 'OK') {
                    alert('Dados atualizados com sucesso!');
                    fecharModalEdicao();
                    location.reload();
                } else {
                    alert('Erro: ' + texto);
                }
            })
            .catch(() => alert('Erro na comunicação com o servidor.'));
        });
        
        function calcularGarantiaEdicao() {
            const dataServico = document.getElementById('editar_data_servico').value;
            const meses = parseInt(document.getElementById('editar_meses_garantia').value);
        
            if (!dataServico || !meses) {
                alert('Preencha a data do serviço e a garantia.');
                return false;
            }
        
            const data = new Date(dataServico);
            data.setMonth(data.getMonth() + meses);
            const garantiaFormatada = data.toISOString().split('T')[0];
            document.getElementById('editar_garantia_servico').value = garantiaFormatada;
            return true;
        }
    </script>
</body>
</html>
<?php
mysqli_close($conn);
?>
