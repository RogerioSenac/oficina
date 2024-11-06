<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Buscar o último idLogin cadastrado na tabela logins
try {
    $stmt = $pdo->query("SELECT idLogin FROM logins ORDER BY idLogin DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se algum idLogin foi encontrado
    if ($row) {
        $idLogin = $row['idLogin'];  // Captura o último idLogin cadastrado
    } else {
        echo "<p>Não foi possível encontrar um login válido.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro ao buscar idLogin: " . $e->getMessage();
    exit;
}

?>

<form action="processar_cadastro_pessoa.php" method="POST">
    <h2>Cadastrar Pessoa</h2>

    <!-- Campo oculto para enviar o idLogin -->
    <input type="hidden" name="idLogin" value="<?php echo $idLogin; ?>">

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

    <!-- Campos de Tipo de Pessoa -->
    <label for="pessoa">Tipo de Pessoa:</label>
    <select id="pessoa" name="pessoa">
        <option value="Pessoa Física">Pessoa Física</option>
        <option value="Pessoa Jurídica">Pessoa Jurídica</option>
    </select>

    <button type="submit">Cadastrar</button>
</form>

<?php
include_once '../includes/footer.php';
?>
