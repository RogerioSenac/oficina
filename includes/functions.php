<?php
/* login */

// function validarLogin($username, $password) {
//     global $pdo;

//     $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = :username AND password = :password');
//     $stmt->execute(['username' => $username, 'password' => md5($password)]);

//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// includes/functions.php

function verificarLogin($pdo, $username, $password) {
    try {
        // Consultar no banco se o usuário e a senha existem
        $stmt = $pdo->prepare("SELECT * FROM logins WHERE usuario = :username AND senha = :password");
        $stmt->execute(['username' => $username, 'password' => md5($password)]);  // Utilizando md5 (ou considere usar password_hash na produção)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;  // Retorna falso em caso de erro
    }
}

function obterTipoUsuario($pdo, $idLogin) {
    try {
        // Buscar o tipoUsuario na tabela cadPessoas
        $stmtTipoUsuario = $pdo->prepare("SELECT tipoUsuario FROM cadPessoas WHERE idLogin = :idLogin");
        $stmtTipoUsuario->execute(['idLogin' => $idLogin]);
        return $stmtTipoUsuario->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;  // Retorna falso em caso de erro
    }
}

function redirecionarTipoUsuario($tipoUsuario) {
    if ($tipoUsuario == 3) {
        // Se tipoUsuario for 3, redireciona para a página do cliente
        header('Location: dashCliente.php');
        exit;
    } elseif ($tipoUsuario == 1 || $tipoUsuario == 2) {
        // Se tipoUsuario for 1 ou 2, redireciona para a página da oficina
        header('Location: dashOficina.php');
        exit;
    }
}


/*---------  Clientes PF ------------------- */



// Função para validar o idLogin
function validarIdLogin($idLogin) {
    if (empty($idLogin) || !is_numeric($idLogin)) {
        return false; // Retorna falso se o idLogin não for válido
    }
    return true; // Retorna verdadeiro se o idLogin for válido
}

// Função para realizar o cadastro de uma pessoa física
function cadastrarPessoaFisica($pdo, $idLogin, $nome, $cpf, $data_nasc, $rua, $numero, $bairro, $cidade, $estado, $cep) {
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

        // Inserir dados na tabela de pessoa física (fisico)
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

        return true; // Retorna verdadeiro se o cadastro for bem-sucedido
    } catch (PDOException $e) {
        // Em caso de erro, desfazemos a transação
        $pdo->rollBack();
        return false; // Retorna falso se ocorrer um erro durante o processo
    }
}




/**-------- Cleintes PJ -------------------- */
?>