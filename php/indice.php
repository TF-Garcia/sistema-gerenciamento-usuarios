<?php
include_once("conexao.php");

// Garantia padrão
$garantia = isset($_GET['garantia']) ? $_GET['garantia'] : '3';

// Consulta com JOIN para filtrar pelos serviços
if ($garantia === 'todos') {
    $sql = "SELECT c.carro_id, c.dono, c.placa, c.modelo
            FROM carros c
            INNER JOIN servicos s ON c.carro_id = s.carro_id
            ORDER BY c.dono ASC";
} else {
    $garantia_int = intval($garantia);
    $sql = "SELECT c.carro_id, c.dono, c.placa, c.modelo
            FROM carros c
            INNER JOIN servicos s ON c.carro_id = s.carro_id
            WHERE s.meses_garantia = $garantia_int
            ORDER BY c.dono ASC";
}

$resultado = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../styles/indice.css"> <!-- CSS externo -->
    <title>Gerenciamento de Clientes - Oficina Mecânica</title>
</head>
<body>

<header>
    <!-- Dropdown de Filtros -->
    <div class="dropdown">
        <button class="dropbtn" onclick="toggleDropdown('filtrosDropdown')">Filtros</button>
        <div id="filtrosDropdown" class="dropdown-content">
            <a href="?garantia=todos" class="<?php echo $garantia === 'todos' ? 'ativo' : ''; ?>">Todos</a>
            <a href="?garantia=3" class="<?php echo $garantia === '3' ? 'ativo' : ''; ?>">3 meses</a>
            <a href="?garantia=6" class="<?php echo $garantia === '6' ? 'ativo' : ''; ?>">6 meses</a>
            <a href="?garantia=9" class="<?php echo $garantia === '9' ? 'ativo' : ''; ?>">9 meses</a>
        </div>
    </div>

    <a href="#" onclick="abrirModalCadastro()">Cadastrar Carros</a>

    <!-- Dropdown Gerenciar Sistema -->
    <div class="dropdown">
        <button class="dropbtn" onclick="toggleDropdown('sistemaDropdown')">Gerenciar Sistema</button>
        <div id="sistemaDropdown" class="dropdown-content">
            <a href="senha.php">Definir/Trocar Senha</a>
            <a href="paginador.php">Gerenciar Usuários</a>
        </div>
    </div>
</header>

<div class="search-container">
    <input type="text" id="searchInput" placeholder="Pesquisar por dono, placa ou modelo..." autocomplete="off">
</div>

<div class="container" id="cardsContainer">
<?php
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        echo "<div class='card' data-dono='".htmlspecialchars($linha['dono'], ENT_QUOTES)."' data-placa='".htmlspecialchars($linha['placa'], ENT_QUOTES)."' data-modelo='".htmlspecialchars($linha['modelo'], ENT_QUOTES)."' onclick=\"abrirDetalhes(".$linha['carro_id'].")\">";
        echo "<strong>Dono: " . htmlspecialchars($linha['dono']) . "</strong>";
        echo "Placa: " . htmlspecialchars($linha['placa']) . "<br>";
        echo "Modelo: " . htmlspecialchars($linha['modelo']);
        echo "</div>";
    }
} else {
    if ($garantia === 'todos') {
        echo "<p class='no-results'>Nenhum cliente cadastrado.</p>";
    } else {
        echo "<p class='no-results'>Nenhum cliente cadastrado para esta garantia.</p>";
    }
}
?>
</div>

<!-- Modal de Cadastro -->
<div class="modal" id="modalCadastro">
    <div class="modal-content">
        <span class="close-modal" onclick="fecharModalCadastro()">&times;</span>
        <h2>Cadastrar Cliente/Carro/Serviço</h2>
        <form id="formCadastro" method="POST">
            <input type="hidden" name="carro_id" id="carro_id">

            <label>CPF:</label>
            <input type="text" name="cpf" maxlength="11" required>
            <label>Nome do Cliente:</label>
            <input type="text" name="dono" required>
            <label>Placa:</label>
            <input type="text" name="placa" maxlength="10" required>
            <label>Modelo:</label>
            <input type="text" name="modelo">

            <label>Descrição do Serviço:</label>
            <textarea name="descricao" required></textarea>
            <label>Valor:</label>
            <input type="number" step="0.01" name="valor" required>
            <label>Data do Serviço:</label>
            <input type="date" id="data_servico" name="data_servico" required>

            <label>Meses de Garantia:</label>
            <select id="meses_garantia" name="meses_garantia" required>
                <option value="">Selecione</option>
                <option value="3">3 meses</option>
                <option value="6">6 meses</option>
                <option value="9">9 meses</option>
            </select>
            <input type="hidden" name="garantia_servico" id="garantia_servico">

            <h3>Peças trocadas</h3>
            <div id="pecas-container">
                <div>
                    <label>Nome da Peça:</label>
                    <input type="text" name="peca_nome[]">
                    <label>Garantia da Peça:</label>
                    <input type="date" name="peca_garantia[]">
                </div>
            </div>
            <button class="btn-edit" type="button" onclick="adicionarPeca()">Adicionar outra peça</button>
            <button type="submit">Salvar</button>
        </form>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal" id="modalDetalhes">
    <div class="modal-content">
        <span class="close-modal" onclick="fecharModalDetalhes()">&times;</span>
        <div id="conteudo-detalhes">
            <!-- Conteúdo carregado por AJAX -->
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal" id="modalEdicao">
    <div class="modal-content">
        <span class="close-modal" onclick="fecharModalEdicao()">&times;</span>
        <h2>Editar Cliente/Carro/Serviço</h2>
        <form id="formEdicao" method="POST">
            <input type="hidden" name="carro_id" id="editar_carro_id">
            <input type="hidden" name="editar" value="1">

            <label>CPF:</label>
            <input type="text" name="cpf" id="editar_cpf" maxlength="11" required>
            <label>Nome do Cliente:</label>
            <input type="text" name="dono" id="editar_dono" required>
            <label>Placa:</label>
            <input type="text" name="placa" id="editar_placa" maxlength="10" required>
            <label>Modelo:</label>
            <input type="text" name="modelo" id="editar_modelo">

            <label>Descrição do Serviço:</label>
            <textarea name="descricao" id="editar_descricao" required></textarea>
            <label>Valor:</label>
            <input type="number" step="0.01" name="valor" id="editar_valor" required>
            <label>Data do Serviço:</label>
            <input type="date" id="editar_data_servico" name="data_servico" required>

            <label>Meses de Garantia:</label>
            <select id="editar_meses_garantia" name="meses_garantia" required>
                <option value="">Selecione</option>
                <option value="3">3 meses</option>
                <option value="6">6 meses</option>
                <option value="9">9 meses</option>
            </select>
            <input type="hidden" name="garantia_servico" id="editar_garantia_servico">

            <h3>Peças trocadas</h3>
            <div id="editar-pecas-container"></div>
            <button type="button" onclick="adicionarPecaEdicao()">Adicionar outra peça</button>
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</div>

<script src="../scripts/funcoes01.js"></script> <!-- JavaScript externo -->

<script>
    /*
    // Armazena todos os cards originais
    const originalCards = document.getElementById('cardsContainer').innerHTML;
    
    // Função para alternar dropdowns
    function toggleDropdown(id) {
        document.querySelectorAll('.dropdown-content').forEach(dc => {
            if (dc.id !== id) {
                dc.style.display = 'none';
            }
        });
        const dropdown = document.getElementById(id);
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    }
    
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
    
    function abrirDetalhes(id) {
        fetch('detalhes.php?id=' + id)
            .then(response => response.text())
            .then(html => {
                document.getElementById('conteudo-detalhes').innerHTML = html;
                document.getElementById('modalDetalhes').style.display = 'flex';
            })
            .catch(() => alert('Erro ao carregar detalhes.'));
    }
    
    function fecharModalDetalhes() {
        document.getElementById('modalDetalhes').style.display = 'none';
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

    // Função para filtrar os cards
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.trim().toLowerCase();
        const cardsContainer = document.getElementById('cardsContainer');
        
        if (searchTerm === '') {
            cardsContainer.innerHTML = originalCards;
            return;
        }
        
        const cards = document.querySelectorAll('.card');
        let hasResults = false;
        
        cards.forEach(card => {
            const dono = card.getAttribute('data-dono').toLowerCase();
            const placa = card.getAttribute('data-placa').toLowerCase();
            const modelo = card.getAttribute('data-modelo').toLowerCase();
            
            if (dono.includes(searchTerm) || placa.includes(searchTerm) || modelo.includes(searchTerm)) {
                card.style.display = 'block';
                hasResults = true;
            } else {
                card.style.display = 'none';
            }
        });
        
        const noResultsMessage = cardsContainer.querySelector('.no-results');
        if (!hasResults) {
            if (!noResultsMessage) {
                const message = document.createElement('p');
                message.className = 'no-results';
                message.textContent = 'Nenhum resultado encontrado.';
                cardsContainer.appendChild(message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    });
    
    // Fechar modais ao clicar fora
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('modalCadastro')) fecharModalCadastro();
        if (event.target === document.getElementById('modalDetalhes')) fecharModalDetalhes();
        if (event.target === document.getElementById('modalEdicao')) fecharModalEdicao();
        if (!event.target.matches('.dropbtn')) {
            document.querySelectorAll('.dropdown-content').forEach(dc => dc.style.display = 'none');
        }
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
    */
</script>
</body>
</html>
