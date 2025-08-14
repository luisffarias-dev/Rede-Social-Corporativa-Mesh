<?php
// Conectar ao banco de dados
include 'conexao.php';

// Iniciar a sessão
session_start();

// Inicializando variáveis para erros
$usuarioErro = $senhaErro = "";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegar os valores do formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta ao banco de dados
    $sql = "SELECT * FROM tb_contas WHERE Email = '$usuario' AND senha = '$senha'";
    $resultado = mysql_query($sql);

    // Verificar se a consulta retornou algum resultado
    if (mysql_num_rows($resultado) > 0) {
        // Login bem-sucedido, salvar o email na sessão
        $_SESSION['email_usuario'] = $usuario;

        // Redireciona para a página de interface
        header("Location: chamar_publis.html");
        exit();
    } else {
        // Senão, exibe mensagem de erro
        echo "<script>alert('Login inválido! E-mail ou senha incorretos.');</script>";
        echo " <center> <a href=\"login.html\">VOLTAR</a> </center>"; 
    }
}
?>
