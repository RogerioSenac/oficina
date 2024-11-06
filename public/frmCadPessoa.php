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
        echo'' . $idLogin .'';
    } else {
        echo "<p>Não foi possível encontrar um login válido.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro ao buscar idLogin: " . $e->getMessage();
    exit;
}
?>

<form id="formTipoPessoa" action="frmCadPessoaFisica.php" method="POST">
    <h2>Cadastrar Pessoa</h2>

    <!-- Campo oculto para enviar o idLogin -->
    <input type="hidden" name="idLogin" value="<?php echo $idLogin; ?>">

    <!-- Campos de Tipo de Pessoa -->
    <label for="pessoa">Tipo de Pessoa:</label>
    <select id="pessoa" name="pessoa" onchange="mostrarFormulario(this.value)">
        <option value="Selecione uma opção">Selecione uma opção</option>
        <option value="Pessoa Física">Pessoa Física</option>
        <option value="Pessoa Jurídica">Pessoa Jurídica</option>
    </select>

    <button type="submit">Avançar</button>
</form>

<script>
function mostrarFormulario(tipoPessoa) {
    // Quando o tipo de pessoa for selecionado, redireciona para o formulário específico
    if (tipoPessoa === "Pessoa Física") {
        // Envia o idLogin via POST ao redirecionar para a página de cadastro de Pessoa Física
        var form = document.getElementById('formTipoPessoa');
        form.action = "frmCadPessoaFisica.php";  // Página para cadastro de Pessoa Física
        form.submit();  // Submete o formulário com idLogin e tipoPessoa via POST
    } else if (tipoPessoa === "Pessoa Jurídica") {
        // Caso seja Pessoa Jurídica, redireciona para a página de cadastro de Pessoa Jurídica
        window.location.href = "frmCadPessoaJuridica.php";  // Página para cadastro de Pessoa Jurídica
    }
}
</script>

<?php
include_once '../includes/footer.php';
?>
