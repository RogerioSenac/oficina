<?php
// Incluir cabeçalhos, funções, etc.
include_once '../includes/header.php';

// Conectar ao banco de dados
include_once '../assets/db/conexao.php';

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $idLogin = $_POST['idLogin'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $tipoUsuario = $_POST['tipoUsuario'];
    $pessoa = $_POST['pessoa'];

    // Tentar inserir os dados no banco
    try {
        $stmt = $pdo->prepare("INSERT INTO cadPessoas (idLogin, rua, numero, bairro, cidade, estado, cep, tipoUsuario, pessoa) 
            VALUES (:idLogin, :rua, :numero, :bairro, :cidade, :estado, :cep, :tipoUsuario, :pessoa)");

        $stmt->execute([
            'idLogin' => $idLogin,
            'rua' => $rua,
            'numero' => $numero,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep,
            'tipoUsuario' => $tipoUsuario,
            'pessoa' => $pessoa
        ]);

        // Se o cadastro for bem-sucedido, redirecionar para uma página de sucesso ou outra página
        echo "<p>Cadastro realizado com sucesso!</p>";
        echo "<a href='index.php'>Voltar à página inicial</a>";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar pessoa: " . $e->getMessage();
    }
}

include_once '../includes/footer.php';
?>
