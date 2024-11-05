<?php
session_start(); // Inicia a sessão

// Incluir o arquivo de conexão com o banco de dados
include './assets/db/conexao.php';

// Variáveis para mensagens
$mensagem_erro = '';
$mensagem_sucesso = '';

// Verificar se foi feito um login ou cadastro
if (isset($_POST['acao'])) {

    // Capturando os dados do formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if ($_POST['acao'] == 'login') {
        // Lógica de Login

        try {
            $sql = "SELECT * FROM logins WHERE usuario = :usuario LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            // Verificar se o usuário existe
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar se a senha confere
                if (password_verify($senha, $user['senha'])) {
                    // Login bem-sucedido
                    $_SESSION['usuario'] = $usuario;
                    header('Location: dashboard.php'); // Redireciona para a página do dashboard
                    exit;
                } else {
                    // Senha incorreta
                    $mensagem_erro = 'Usuário ou senha incorretos.';
                }
            } else {
                // Usuário não encontrado
                $mensagem_erro = 'Usuário não cadastrado. Por favor, cadastre-se.';
                // Redireciona para o formulário de cadastro
                header("Location: frmCadLogin.php?usuario=$usuario");
                exit;
            }
        } catch (PDOException $e) {
            // Erro ao acessar o banco
            $mensagem_erro = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
        }
    } elseif ($_POST['acao'] == 'cadastrar') {
        // Lógica de Cadastro

        try {
            // Verificar se o usuário já existe
            $sql = "SELECT * FROM logins WHERE usuario = :usuario LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Usuário já existe
                $mensagem_erro = 'Usuário já cadastrado. Tente outro nome.';
            } else {
                // Cadastrar o novo usuário com a senha criptografada
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = "INSERT INTO logins (usuario, senha) VALUES (:usuario, :senha)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':usuario', $usuario);
                $stmt->bindParam(':senha', $senha_hash);
                $stmt->execute();

                // Captura o idLogin do usuário recém-cadastrado
                $idLogin = $conn->lastInsertId();

                // Cadastro bem-sucedido
                $mensagem_sucesso = 'Cadastro realizado com sucesso! Agora você pode cadastrar os dados da pessoa.';

                // Redireciona para o formulário de cadastro da pessoa, enviando o idLogin via POST
                header("Location: frmCadPessoa.php?idLogin=" . $idLogin);
                exit;
            }
        } catch (PDOException $e) {
            // Erro ao acessar o banco
            $mensagem_erro = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Exibir mensagem de erro -->
    <?php if ($mensagem_erro): ?>
        <p style="color:red;"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>

    <!-- Exibir mensagem de sucesso no cadastro -->
    <?php if ($mensagem_sucesso): ?>
        <p style="color:green;"><?php echo $mensagem_sucesso; ?></p>
    <?php endif; ?>

    <!-- Formulário de Login -->
    <form method="POST" action="frmlogin.php">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <button type="submit" name="acao" value="login">Entrar</button>
    </form>

    <p>Ainda não tem conta? <a href="frmCadLogin.php?acao=cadastrar">Cadastre-se</a></p>

    <?php
    // Verifica se a ação é de cadastro
    if (isset($_GET['acao']) && $_GET['acao'] == 'cadastrar') {
        ?>
        <h2>Cadastro de Novo Usuário</h2>
        <form method="POST" action="frmCadLogin.php">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required><br><br>
            
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="submit" name="acao" value="cadastrar">Cadastrar</button>
        </form>
        <?php
    }
    ?>
</body>
</html>
