<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Variáveis para controle da mensagem de erro
$loginError = false; // Flag de erro para login

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar no banco se o usuário e a senha existem
    try {
        $stmt = $pdo->prepare("SELECT * FROM logins WHERE usuario = :username AND senha = :password");
        $stmt->execute(['username' => $username, 'password' => md5($password)]);  // Utilizando md5 (ou considere usar password_hash na produção)
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Se usuário encontrado, redireciona para a página principal ou dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            // Se não encontrar o usuário, ativa a flag de erro
            $loginError = true;
        }
    } catch (PDOException $e) {
        echo "Erro ao verificar login: " . $e->getMessage();
        exit;
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
