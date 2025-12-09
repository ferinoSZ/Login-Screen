<?php
/*
    ARQUIVO DE EXEMPLO
    1. Renomeie este arquivo para "conexao.php"
    2. Coloque as credenciais do seu banco de dados abaixo
*/

$host = "localhost";
$user = "root";        // Usuário padrão do XAMPP
$pass = "";            // Senha padrão do XAMPP (geralmente vazia)
$dbname = "nome_do_seu_banco"; // COLOQUE O NOME DO SEU BANCO AQUI
$port = 3306;

try {
    // Conexão com a porta (alguns XAMPP usam portas diferentes)
    $conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);
    
    // Configura o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão realizada com sucesso!"; 
    
} catch(PDOException $err) {
    echo "Erro na conexão: " . $err->getMessage();
}
?>