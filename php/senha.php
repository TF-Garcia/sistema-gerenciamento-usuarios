<?php
session_start();
include_once("conexao.php");
include_once("cryp2graph2.php"); // Funções de criptografia personalizadas

$mensagem = "";

// Consulta o usuário existente
$sql = "SELECT * FROM login LIMIT 1";
$result = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($result);

// Situações possíveis
$temUsuario = is_array($usuario);
$temSenha   = $temUsuario && !empty($usuario['senha']);

// Processamento de formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Criar usuário
    if (isset($_POST['criarUsuario'])) {
        $novoUsuario = mysqli_real_escape_string($conn, $_POST['usuario']);
        $novoEmail1  = mysqli_real_escape_string($conn, $_POST['email1']);
        $novoEmail2  = mysqli_real_escape_string($conn, $_POST['email2']);
        $senha1      = $_POST['senha1'];
        $senha2      = $_POST['senha2'];

        if ($novoEmail1 !== $novoEmail2) {
            $mensagem = "Os e-mails não coincidem.";
        } elseif (!filter_var($novoEmail1, FILTER_VALIDATE_EMAIL)) {
            $mensagem = "E-mail inválido.";
        } elseif ($senha1 !== $senha2) {
            $mensagem = "As senhas não coincidem.";
        } elseif (strlen($senha1) < 6) {
            $mensagem = "A senha deve ter pelo menos 6 caracteres.";
        } elseif (!$temUsuario) {
            $novaSenha = FazSenha($novoUsuario, $senha1);

            $insert = "INSERT INTO login (usuario, senha, email) VALUES ('$novoUsuario', '$novaSenha', '$novoEmail1')";
            if (mysqli_query($conn, $insert)) {
                $mensagem = "Usuário criado com sucesso!";
                $temUsuario = true;
                $temSenha = true;
                $usuario = ['usuario' => $novoUsuario, 'senha' => $novaSenha, 'email' => $novoEmail1];
            } else {
                $mensagem = "Erro ao criar usuário: " . mysqli_error($conn);
            }
        } else {
            $mensagem = "Já existe um usuário cadastrado.";
        }
    }

    // Definir senha
    if ($temUsuario && isset($_POST['definirSenha'])) {
        $senha1 = $_POST['senha1'];
        $senha2 = $_POST['senha2'];

        if ($senha1 !== $senha2) {
            $mensagem = "As senhas não coincidem.";
        } elseif (strlen($senha1) < 6) {
            $mensagem = "A senha deve ter pelo menos 6 caracteres.";
        } else {
            $novaSenha = FazSenha($usuario['usuario'], $senha1);
            $update = "UPDATE login SET senha = '$novaSenha' WHERE usuario = '".$usuario['usuario']."'";
            if (mysqli_query($conn, $update)) {
                $mensagem = "Senha definida com sucesso!";
                $temSenha = true;
                $usuario['senha'] = $novaSenha;
            } else {
                $mensagem = "Erro ao definir senha.";
            }
        }
    }

    // Alterar senha
    if ($temUsuario && isset($_POST['alterarSenha'])) {
        $senhaAtual = $_POST['senhaAtual'];
        $senha1     = $_POST['senha1'];
        $senha2     = $_POST['senha2'];

        if (ChecaSenha($senhaAtual, $usuario['senha'])) {
            if ($senha1 !== $senha2) {
                $mensagem = "Nova senha e confirmação não coincidem.";
            } elseif (strlen($senha1) < 6) {
                $mensagem = "A nova senha deve ter pelo menos 6 caracteres.";
            } else {
                $senhaHashNova = FazSenha($usuario['usuario'], $senha1);
                $update = "UPDATE login SET senha = '$senhaHashNova' WHERE usuario = '".$usuario['usuario']."'";
                if (mysqli_query($conn, $update)) {
                    $mensagem = "Senha alterada com sucesso!";
                    $usuario['senha'] = $senhaHashNova;
                } else {
                    $mensagem = "Erro ao alterar senha.";
                }
            }
        } else {
            $mensagem = "Senha atual incorreta.";
        }
    }

    // Remover senha
    if ($temUsuario && isset($_POST['removerSenha'])) {
        $senhaAtual = $_POST['senhaAtual'];

        if (empty($senhaAtual)) {
            $mensagem = "Digite a senha atual para remover.";
        } elseif (ChecaSenha($senhaAtual, $usuario['senha'])) {
            $update = "UPDATE login SET senha = '' WHERE usuario = '".$usuario['usuario']."'";
            if (mysqli_query($conn, $update)) {
                $mensagem = "Senha removida com sucesso!";
                $temSenha = false;
                $usuario['senha'] = '';
            } else {
                $mensagem = "Erro ao remover senha.";
            }
        } else {
            $mensagem = "Senha atual incorreta. Não foi possível remover.";
        }
    }

    // Alterar email
    if ($temUsuario && isset($_POST['alterarEmail'])) {
        $novoEmail1 = mysqli_real_escape_string($conn, $_POST['novoEmail1']);
        $novoEmail2 = mysqli_real_escape_string($conn, $_POST['novoEmail2']);

        if ($novoEmail1 !== $novoEmail2) {
            $mensagem = "Os e-mails não coincidem.";
        } elseif (!filter_var($novoEmail1, FILTER_VALIDATE_EMAIL)) {
            $mensagem = "E-mail inválido.";
        } else {
            $update = "UPDATE login SET email = '$novoEmail1' WHERE usuario = '".$usuario['usuario']."'";
            if (mysqli_query($conn, $update)) {
                $mensagem = "Email alterado com sucesso!";
                $usuario['email'] = $novoEmail1;
            } else {
                $mensagem = "Erro ao alterar email: " . mysqli_error($conn);
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #2E3A3B;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center; /* centraliza verticalmente */
            gap: 20px;
            position: sticky;
            top: 0;
            flex-wrap: wrap;
            z-index: 1000;
        }

        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        header a:hover {
            color: #1abc9c;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #3C546C;
            color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #54B0A7;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #3A8F84;
        }

        p.mensagem {
            text-align: center;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <a href="indice.php">Página Inicial</a>
        <a href="paginador.php">Gerenciar Usuários</a>
    </header>

    <div class="container">
        <h2>Gerenciamento de Usuário</h2>

        <?php if (!$temUsuario): ?>
            <form method="post">
                <label>Usuário:</label>
                <input type="text" name="usuario" required>

                <label>Email:</label>
                <input type="email" name="email1" required>

                <label>Repita o email:</label>
                <input type="email" name="email2" required>

                <label>Senha:</label>
                <input type="password" name="senha1" required>

                <label>Repita a senha:</label>
                <input type="password" name="senha2" required>

                <button type="submit" name="criarUsuario">Criar Usuário</button>
            </form>

        <?php elseif ($temUsuario && !$temSenha): ?>
            <p>Usuário encontrado: <strong><?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?></strong></p>
            <form method="post">
                <label>Nova Senha:</label>
                <input type="password" name="senha1" required>

                <label>Repita a senha:</label>
                <input type="password" name="senha2" required>

                <button type="submit" name="definirSenha">Definir Senha</button>
            </form>

        <?php elseif ($temUsuario && $temSenha): ?>
            <p>Usuário: <strong><?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?></strong></p>
            <p>Email: <strong><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></strong></p>

            <form method="post">
                <label>Senha Atual:</label>
                <input type="password" name="senhaAtual" required>

                <label>Nova senha:</label>
                <input type="password" name="senha1" required>

                <label>Confirmar nova senha:</label>
                <input type="password" name="senha2" required>

                <button type="submit" name="alterarSenha">Alterar Senha</button>
            </form>

            <form method="post" style="margin-top:20px;">
                <label>Senha Atual para remover:</label>
                <input type="password" name="senhaAtual" required>
                <button type="submit" name="removerSenha" onclick="return confirm('Tem certeza que deseja remover a senha?')">Remover Senha</button>
            </form>

            <form method="post" style="margin-top:20px;">
                <label>Novo Email:</label>
                <input type="email" name="novoEmail1" required>

                <label>Repita o novo email:</label>
                <input type="email" name="novoEmail2" required>

                <button type="submit" name="alterarEmail">Alterar Email</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($mensagem)): ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
