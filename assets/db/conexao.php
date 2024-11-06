<?php
// Definindo as variáveis de conexão
$servidor = "localhost";    // ou o endereço do servidor MySQL
$usuario = "root";       // substitua pelo nome de usuário do banco de dados
$senha = "";           // substitua pela senha do banco de dados
$banco = "mazzoline";       // nome do banco de dados

try {
    // Criando a conexão usando PDO
    $dsn = "mysql:host=$servidor;dbname=$banco;charset=utf8"; // DSN (Data Source Name)
    $pdo = new PDO($dsn, $usuario, $senha);

    // Configurando o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Caso a conexão seja bem-sucedida
    // echo "Conexão bem-sucedida!";
    
} catch (PDOException $e) {
    // Caso ocorra algum erro na conexão, exibe a mensagem de erro
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Retornando a conexão para ser utilizada em outros arquivos
return $pdo;
?>
