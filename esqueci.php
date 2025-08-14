<?php
include 'conexao.php';



$email = $_POST["email"];
$senha = $_POST["senha"];
$confisenha=$_POST["confsenha"];

if($senha != $confisenha){
    echo "<p style='color:red;'>As senhas não coincidem!</p>"; 
  
}
else{

$sql = "SELECT Email FROM tb_contas where (Email='$email')";

$query= mysql_query($sql);

if (mysql_num_rows($query)==0){
    echo "Conta Não Encontrada";
}
else{

$sql2 = "UPDATE tb_contas SET Senha = '$senha' WHERE Email = '$email'";
if (mysql_query($sql2)) {
    header("Location: login.html");
    exit();
}

else {
  
    echo "Erro ao atualizar a senha: " . mysql_error();
    echo "<a href=\"login.html\">VOLTAR</a>"; 
}
}}

?>