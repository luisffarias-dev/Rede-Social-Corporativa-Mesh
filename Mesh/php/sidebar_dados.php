<?php
include 'conexao.php';

session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['email_usuario'])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: index.html");
    exit();
}

// Obtém o e-mail do usuário logado da sessão
$email = $_SESSION['email_usuario'];

// Conecta ao banco de dados
$conecta_db = mysql_connect($servidor, $usuario, $senha) or die(mysql_error());
mysql_select_db($banco) or die("Erro ao conectar ao banco de dados");

// Query para buscar dados do usuário 
$query = "SELECT Nome, Imagem FROM tb_contas WHERE Email = '$email'";
$result = mysql_query($query);

// Verifica se a consulta foi executada corretamente
if (!$result) {
    die('Erro na consulta: ' . mysql_error());
}

// Verifica se encontrou algum resultado
if ($row = mysql_fetch_assoc($result)) {
    // Atribui os dados às variáveis
    $nome_usuario = $row['Nome'];
    $imagem_usuario = $row['Imagem'];
} else {
    $nome_usuario = "Usuário não encontrado";
    $imagem_usuario = "default.jpg"; // Exemplo de imagem padrão
}
?>

<!-- HTML -->



