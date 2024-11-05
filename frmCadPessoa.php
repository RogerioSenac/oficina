<?php
session_start(); // Inicia a sessão

// Incluir o arquivo de conexão com o banco de dados
include './assets/db/conexao.php';

// Variáveis para mensagens
$mensagem_erro = '';
$mensagem_sucesso = '';

// Verificar se o idLogin foi passado via POST
if (isset($_POST['idLogin'])) {
    $idLogin = $_POST['idLogin'];
} else {
    // Caso o idLogin não seja passado corretamente, redireciona para a página de login
    header('Location: login.php');
    exit;
}

// Verificar se o formulário foi enviado
if (isset($_POST['acao']) && $_POST['acao'] == 'cadastrarPessoa') {
    // Captura os dados do formulário
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $tipoPessoa = $_POST['tipoPessoa'];
    $pessoa = $_POST['pessoa'];

    // Lógica para cadastrar os dados da pessoa
    try {
        // Inserir os dados da pessoa na tabela cadPessoas
        $sql = "INSERT INTO cadPessoas (idLogin, rua, numero, bairro, cidade, estado, cep, tipoUsuario, pessoa) 
                VALUES (:idLogin, :rua, :numero, :bairro, :cidade, :estado, :cep, :tipoPessoa, :pessoa)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idLogin', $idLogin);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':tipoPessoa', $tipoPessoa);
        $stmt->bindParam(':pessoa', $pessoa);
        $stmt->execute();

        // Cadastro bem-sucedido
        $mensagem_sucesso = 'Cadastro da pessoa realizado com sucesso!';

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
    <title>Cadastro de Pessoa</title>
</head>
<body>
    <h2>Cadastro de Pessoa</h2>

    <!-- Exibir mensagem de erro -->
    <?php if ($mensagem_erro): ?>
        <p style="color:red;"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>

    <!-- Exibir mensagem de sucesso no cadastro -->
    <?php if ($mensagem_sucesso): ?>
        <p style="color:green;"><?php echo $mensagem_sucesso; ?></p>
    <?php endif; ?>

    <!-- Formulário de Cadastro de Pessoa -->
    <form method="POST" action="frmCadPessoa.php">
        <input type="hidden" name="idLogin" value="<?php echo $idLogin; ?>">

        <label for="rua">Rua:</label>
        <input type="text" id="rua" name="rua" required><br><br>

        <label for="numero">Número:</label>
        <input type="text" id="numero" name="numero" required><br><br>

        <label for="bairro">Bairro:</label>
        <input type="text" id="bairro" name="bairro" required><br><br>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" required><br><br>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required><br><br>

        <label for="cep">CEP:</label>
        <input type="number" id="cep" name="cep" required><br><br>

        <label for="tipoPessoa">Tipo de Pessoa:</label>
        <select id="tipoPessoa" name="tipoPessoa">
            <option value="3">Pessoa Física</option>
            <option value="1">Pessoa Jurídica</option>
        </select><br><br>

        <label for="pessoa">Tipo de Cadastro:</label>
        <select id="pessoa" name="pessoa" onchange="toggleFields()">
            <option value="Pessoa Física">Pessoa Física</option>
            <option value="Pessoa Jurídica">Pessoa Jurídica</option>
        </select><br><br>

        <!-- Campos Pessoa Física -->
        <div id="fisicoFields">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome"><br><br>
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf"><br><br>
            <label for="data_nasc">Data de Nascimento:</label>
            <input type="date" id="data_nasc" name="data_nasc"><br><br>
        </div>

        <!-- Campos Pessoa Jurídica -->
        <div id="juridicoFields" style="display:none;">
            <label for="razaoSocial">Razão Social:</label>
            <input type="text" id="razaoSocial" name="razaoSocial"><br><br>
            <label for="nomeFantasia">Nome Fantasia:</label>
            <input type="text" id="nomeFantasia" name="nomeFantasia"><br><br>
            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj"><br><br>
            <label for="inscEstadual">Inscrição Estadual:</label>
            <input type="text" id="inscEstadual" name="inscEstadual"><br><br>
            <label for="data_Fundacao">Data de Fundação:</label>
            <input type="date" id="data_Fundacao" name="data_Fundacao"><br><br>
        </div>

        <button type="submit" name="acao" value="cadastrarPessoa">Cadastrar</button>
    </form>

    <p>Voltar para o <a href="dashboard.php">painel</a></p>

    <script>
        // Função para alternar entre os campos de Pessoa Física e Jurídica
        function toggleFields() {
            var pessoa = document.getElementById("pessoa").value;
            if (pessoa == "Pessoa Física") {
                document.getElementById("fisicoFields").style.display = "block";
                document.getElementById("juridicoFields").style.display = "none";
            } else {
                document.getElementById("fisicoFields").style.display = "none";
                document.getElementById("juridicoFields").style.display = "block";
            }
        }
    </script>
</body>
</html>
