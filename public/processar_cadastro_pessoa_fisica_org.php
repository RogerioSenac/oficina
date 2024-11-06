<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Verificar se os dados do formulário foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar os dados do formulário
    $idLogin = $_POST['idLogin'];   // idLogin que foi passado via POST
    $nome = $_POST['nome'];         // Nome da pessoa
    $cpf = $_POST['cpf'];           // CPF da pessoa
    $data_nasc = $_POST['data_nasc']; // Data de nascimento da pessoa
    $rua = $_POST['rua'];           // Rua
    $numero = $_POST['numero'];     // Número
    $bairro = $_POST['bairro'];     // Bairro
    $cidade = $_POST['cidade'];     // Cidade
    $estado = $_POST['estado'];     // Estado
    $cep = $_POST['cep'];           // CEP

    // Verificar se idLogin foi passado e é válido
    if (empty($idLogin) || !is_numeric($idLogin)) {
        echo "<p>Erro: idLogin não foi informado corretamente.</p>";
        exit;
    }

    try {
        // Iniciar uma transação para garantir a integridade dos dados
        $pdo->beginTransaction();

        // Inserir dados na tabela de pessoas físicas (cadPessoas)
        $stmt = $pdo->prepare("INSERT INTO cadPessoas (idLogin, rua, numero, bairro, cidade, estado, cep, pessoa) 
                               VALUES (:idLogin, :rua, :numero, :bairro, :cidade, :estado, :cep, 'Pessoa Física')");
        $stmt->execute([
            ':idLogin' => $idLogin,
            ':rua' => $rua,
            ':numero' => $numero,
            ':bairro' => $bairro,
            ':cidade' => $cidade,
            ':estado' => $estado,
            ':cep' => $cep
        ]);

        // Capturar o último idPessoa inserido para associar à pessoa física
        $idPessoa = $pdo->lastInsertId();

        // Inserir dados na tabela de pessoa física (cadPF)
        $stmtPF = $pdo->prepare("INSERT INTO fisico (idPessoa, nome, cpf, data_nasc) 
                                 VALUES (:idPessoa, :nome, :cpf, :data_nasc)");
        $stmtPF->execute([
            ':idPessoa' => $idPessoa,
            ':nome' => $nome,
            ':cpf' => $cpf,
            ':data_nasc' => $data_nasc
        ]);

        // Commitar a transação
        $pdo->commit();

        // Mensagem de sucesso e redirecionamento
        echo "<p>Cadastro realizado com sucesso! Você será redirecionado para a página inicial.</p>";
        echo "<a href='../index.php'>Voltar para a página inicial</a>";

    } catch (PDOException $e) {
        // Em caso de erro, desfazemos a transação
        $pdo->rollBack();
        echo "<p>Erro ao realizar o cadastro: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Dados inválidos. O formulário não foi submetido corretamente.</p>";
}

include_once '../includes/footer.php';
?>
