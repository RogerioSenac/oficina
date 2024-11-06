<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';
include_once '../includes/functions.php';  // Incluir o arquivo de funções

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Verifica se os dados de idLogin e tipo de pessoa foram passados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se todos os campos obrigatórios estão presentes no POST
    $idLogin = $_POST['idLogin'] ?? null;  // Usando o operador de coalescência nula
    $nome = $_POST['nome'] ?? '';          // Se não enviado, define um valor padrão
    $cpf = $_POST['cpf'] ?? '';            // Se não enviado, define um valor padrão
    $data_nasc = $_POST['data_nasc'] ?? '';
    $rua = $_POST['rua'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $cep = $_POST['cep'] ?? '';

    // Verifica se o idLogin foi enviado
    if (empty($idLogin) || !is_numeric($idLogin)) {
        echo "<p>Erro: idLogin não foi informado corretamente.</p>";
        exit;
    }

    // Chama a função de cadastro
    $cadastroRealizado = cadastrarPessoaFisica($pdo, $idLogin, $nome, $cpf, $data_nasc, $rua, $numero, $bairro, $cidade, $estado, $cep);

    if ($cadastroRealizado) {
        echo "<p>Cadastro realizado com sucesso!</p>";
        // Redireciona para a página inicial ou para o dashboard, conforme a necessidade
        header('Location: index.php');
        exit;
    } else {
        echo "<p>Erro ao realizar o cadastro. Tente novamente.</p>";
    }
} else {
    echo "<p>Erro: O formulário não foi submetido corretamente.</p>";
    exit;
}

// Buscar o idLogin associado ao idPessoa na tabela cadPessoas
try {
    $stmt = $pdo->query("SELECT idLogin FROM logins ORDER BY idLogin DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $idLogin = $row['idLogin'];  // Captura o idLogin encontrado
    } else {
        echo "<p>Não foi possível encontrar o idLogin associado ao cadastro de Pessoa.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro ao buscar idLogin: " . $e->getMessage();
    exit;
}
?>

<form action="frmCadPessoaFisica.php" method="POST">
    <h2>Cadastrar Pessoa Física</h2>

    <!-- Campo oculto para enviar o idLogin -->
    <input type="hidden" name="idLogin" value="<?php echo htmlspecialchars($idLogin); ?>">

    <!-- Campos específicos para Pessoa Física -->
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required pattern="\d{11}" maxlength="11">

    <label for="data_nasc">Data de Nascimento:</label>
    <input type="date" id="data_nasc" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>" required>

    <!-- Campos de Endereço -->
    <label for="rua">Rua:</label>
    <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($rua); ?>" required>

    <label for="numero">Número:</label>
    <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required>

    <label for="bairro">Bairro:</label>
    <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" required>

    <label for="cidade">Cidade:</label>
    <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>" required>

    <label for="estado">Estado:</label>
    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>" required>

    <label for="cep">CEP:</label>
    <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($cep); ?>" required>

    <label for="pessoa">Tipo de Pessoa:</label>
    <input type="text" id="pessoa" name="pessoa" value="Pessoa Física" readonly>

    <button type="submit">Cadastrar</button>
</form>

<?php
include_once '../includes/footer.php';
?>
