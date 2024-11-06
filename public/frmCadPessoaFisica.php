<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Verifica se os dados de idLogin e tipo de pessoa foram passados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idLogin = $_POST['idLogin'];  // Recebe o idLogin enviado
    echo "idLogin:  $idLogin";
    $pessoa = $_POST['pessoa'];    // Recebe o tipo de pessoa
} else {
    echo "<p>Erro: idLogin não foi enviado corretamente.</p>";
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

<form action="processar_cadastro_pessoa_fisica.php" method="POST">
    <h2>Cadastrar Pessoa Física</h2>

    <!-- Campo oculto para enviar o idLogin -->
    <input type="hidden" name="idLogin" value="<?php echo $idLogin; ?>">

    <!-- Campos específicos para Pessoa Física -->
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" required pattern="\d{11}" maxlength="11">

    <label for="data_nasc">Data de Nascimento:</label>
    <input type="date" id="data_nasc" name="data_nasc" required>

    <!-- Campos de Endereço -->
    <label for="rua">Rua:</label>
    <input type="text" id="rua" name="rua" required>

    <label for="numero">Número:</label>
    <input type="text" id="numero" name="numero" required>

    <label for="bairro">Bairro:</label>
    <input type="text" id="bairro" name="bairro" required>

    <label for="cidade">Cidade:</label>
    <input type="text" id="cidade" name="cidade" required>

    <label for="estado">Estado:</label>
    <input type="text" id="estado" name="estado" required>

    <label for="cep">CEP:</label>
    <input type="text" id="cep" name="cep" required>

    <label for="pessoa">Tipo de Pessoa:</label>
    <input type="text" id="pessoa" name="pessoa" value="Pessoa Física" readonly>

    <button type="submit">Cadastrar</button>
</form>

<?php
include_once '../includes/footer.php';
?>
