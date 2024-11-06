<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Incluir funções
include_once '../includes/functions.php';

// Variáveis para controle da mensagem de erro
$loginError = false; // Flag de erro para login

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar no banco se o usuário e a senha existem
    $user = verificarLogin($pdo, $username, $password);

    if ($user) {
        // Se o usuário for encontrado, verifica o tipo de usuário
        $idLogin = $user['idLogin'];
        $pessoa = obterTipoUsuario($pdo, $idLogin);

        if ($pessoa) {
            // Redireciona com base no tipo de usuário
            redirecionarTipoUsuario($pessoa['tipoUsuario']);
        } else {
            echo "<p>Erro: Usuário não encontrado na tabela cadPessoas.</p>";
            exit;
        }
    } else {
        // Se não encontrar o usuário, ativa a flag de erro
        $loginError = true;
    }
}
?>

<?php if ($loginError): ?>
    <!-- Exibe mensagem de erro caso o login falhe -->
    <div id="login-error-message">
        <p><strong>Erro:</strong> Usuário ou senha não encontrados!</p>
        <p>Deseja criar um novo cadastro?</p>
        <form action="frmLogin.php" method="POST">
            <button type="submit" name="action" value="yes">Sim</button>
            <button type="submit" name="action" value="no">Não</button>
        </form>
    </div>
<?php else: ?>
    <!-- Formulário de login -->
    <form action="frmLogin.php" method="POST">
        <h2>Login</h2>
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>
<?php endif; ?>

<?php
// Ações após o clique nos botões "Sim" ou "Não"
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'yes') {
        // Redireciona para a página de cadastro
        header('Location: frmCadLogin.php');
        exit;
    } elseif ($_POST['action'] == 'no') {
        // Redireciona para a página inicial
        header('Location: ../index.php');
        exit;
    }
}

include_once '../includes/footer.php';
?>
