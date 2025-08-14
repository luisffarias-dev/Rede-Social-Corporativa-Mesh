<?php
include 'conexao.php';
 
// Parte para registrar a conta
$nome = $_POST["nome"];
$emailcad = $_POST["email"];
$senha = $_POST["senha"];
$confisenha = $_POST["confsenha"];
$estado = $_POST["estado_trabalho"];
$cidade = $_POST["cidade"];
 
if ($senha != $confisenha) {
    echo "As senhas não coincidem!";
    exit();
}
 
// Verifica se a imagem foi enviada
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
    // Carrega a imagem enviada
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
} else {
    // Carrega a imagem padrão se não houver imagem enviada
    $imagem = file_get_contents('silueta.jpg'); // Apenas o nome da imagem
}
 
// Escapa os dados para evitar injeção SQL
 
$imagem = mysql_real_escape_string($imagem);
 
// Cria a consulta SQL
$sql = "INSERT INTO tb_contas (Imagem, Nome, Email, Senha, Estado, Cidade) VALUES ('$imagem', '$nome', '$emailcad', '$senha', '$estado',' $cidade')";
 
// Executa a consulta
if (mysql_query($sql)) {
    header("Location: login.html");
    exit();
} else {
    echo "Erro ao registrar a conta: " . mysql_error();
}
 
 
?>