<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Verifica se os dados de idLogin e tipo de pessoa foram passados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idLogin = $_POST['idLogin'];
    $pessoa = $_POST['pessoa'];
}

?>

<form action="processar_cadastro_pessoa_juridica.php" method="POST">
    <h2>Cadastrar Pessoa Jurídica</h2>

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

    <label for="pessoa">Tipo de Pessoa:</label>
    <input type="text" id="pessoa" name="pessoa" value="Pessoa Jurídica" readonly>

    <button type="submit">Cadastrar</button>
</form>

<?php
include_once '../includes/footer.php';
?>
