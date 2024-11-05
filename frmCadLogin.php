<?php
session_start(); // Inicia a sessão

// Incluir o arquivo de conexão com o banco de dados
include './assets/db/conexao.php';

// Variáveis para mensagens
$mensagem_erro = '';
$mensagem_sucesso = '';

// Verificar se o formulário foi enviado
if (isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {
    // Captura os dados do formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Lógica para cadastrar o usuário
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
            echo "<script>
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'frmCadPessoa.php';

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'idLogin';
                    input.value = '$idLogin';
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();
                  </script>";
            exit;
        }
    } catch (PDOException $e) {
        // Erro ao acessar o banco
        $mensagem_erro = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Novo Usuário</title>
</head>
<body>
    <h2>Cadastro de Novo Usuário</h2>

    <!-- Exibir mensagem de erro -->
    <?php if ($mensagem_erro): ?>
        <p style="color:red;"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>

    <!-- Exibir mensagem de sucesso no cadastro -->
    <?php if ($mensagem_sucesso): ?>
        <p style="color:green;"><?php echo $mensagem_sucesso; ?></p>
    <?php endif; ?>

    <!-- Formulário de Cadastro -->
    <form method="POST" action="frmCadLogin.php">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <button type="submit" name="acao" value="cadastrar">Cadastrar</button>
    </form>

    <p>Já tem uma conta? <a href="frmLogin.php">Faça login aqui</a></p>
</body>
</html>
