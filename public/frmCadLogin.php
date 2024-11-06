<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Variáveis de controle de mensagem
$cadastroConcluido = false;  // Flag de sucesso para cadastro

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar se o usuário já existe
    try {
        $stmt = $pdo->prepare("SELECT * FROM logins WHERE usuario = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Se o usuário já existe, exibe uma mensagem
            echo "<p>Este nome de usuário já está em uso. Tente outro.</p>";
        } else {
            // Se o usuário não existe, faz o cadastro
            $stmt = $pdo->prepare("INSERT INTO logins (usuario, senha) VALUES (:username, :password)");
            $stmt->execute([
                'username' => $username,
                'password' => md5($password) // Utilizando md5 aqui (novamente, para produção considere password_hash)
            ]);

            // Cadastro concluído com sucesso
            $cadastroConcluido = true;
        }
    } catch (PDOException $e) {
        echo "Erro ao cadastrar usuário: " . $e->getMessage();
    }
}
?>

<?php if ($cadastroConcluido): ?>
    <!-- Mensagem de sucesso após cadastro -->
    <div class="alert-success">
        <p>Usuário cadastrado com sucesso! Você pode agora continuar o processo de cadastro.</p>
        <form action="frmCadPessoa.php" method="GET">
            <button type="submit" class="btn-confirm">Confirmar Cadastro</button>
        </form>
    </div>
<?php else: ?>
    <!-- Formulário de cadastro de login -->
    <form action="frmCadLogin.php" method="POST">
        <h2>Cadastrar Novo Usuário</h2>
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Cadastrar</button>
    </form>
<?php endif; ?>

<?php
include_once '../includes/footer.php';
?>
