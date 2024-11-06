<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar os dados do formulário
    $idLogin = $_POST['idLogin'];  // idLogin recebido
    $razaoSocial = $_POST['razaoSocial']; // Razão Social
    $nomeFantasia = $_POST['nomeFantasia']; // Nome Fantasia
    $cnpj = $_POST['cnpj']; // CNPJ
    $inscEstadual = $_POST['inscEstadual']; // Inscrição Estadual
    $data_Fundacao = $_POST['data_Fundacao']; // Data de Fundação
    $rua = $_POST['rua']; // Rua
    $numero = $_POST['numero']; // Número
    $bairro = $_POST['bairro']; // Bairro
    $cidade = $_POST['cidade']; // Cidade
    $estado = $_POST['estado']; // Estado
    $cep = $_POST['cep']; // CEP

    try {
        // Iniciar uma transação para garantir a integridade dos dados
        $pdo->beginTransaction();

        // Inserir dados na tabela de pessoas (cadPessoas) - endereço
        $stmt = $pdo->prepare("INSERT INTO cadPessoas (idLogin, rua, numero, bairro, cidade, estado, cep, pessoa) 
                               VALUES (:idLogin, :rua, :numero, :bairro, :cidade, :estado, :cep, 'Pessoa Jurídica')");
        $stmt->execute([
            ':idLogin' => $idLogin,
            ':rua' => $rua,
            ':numero' => $numero,
            ':bairro' => $bairro,
            ':cidade' => $cidade,
            ':estado' => $estado,
            ':cep' => $cep
        ]);

        // Capturar o último idPessoa inserido para associar à pessoa jurídica
        $idPessoa = $pdo->lastInsertId();

        // Inserir dados na tabela de pessoa jurídica (cadPJ)
        $stmtPJ = $pdo->prepare("INSERT INTO juridico (idPessoa, razaoSocial, nomeFantasia, cnpj, inscEstadual, data_Fundacao) 
                                 VALUES (:idPessoa, :razaoSocial, :nomeFantasia, :cnpj, :inscEstadual, :data_Fundacao)");
        $stmtPJ->execute([
            ':idPessoa' => $idPessoa,
            ':razaoSocial' => $razaoSocial,
            ':nomeFantasia' => $nomeFantasia,
            ':cnpj' => $cnpj,
            ':inscEstadual' => $inscEstadual,
            ':data_Fundacao' => $data_Fundacao
        ]);

        // Commitar a transação
        $pdo->commit();

        // Mensagem de sucesso
        echo "<p>Cadastro de Pessoa Jurídica realizado com sucesso! Você será redirecionado para a página inicial.</p>";
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
