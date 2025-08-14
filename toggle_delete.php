<?php
include 'conexao.php';  // Certifique-se que este arquivo está funcionando
session_start();

$usuarioEmail = $_SESSION['email_usuario'];

if (isset($_POST['post_id'])) {
    // Debug: Verifique se o post_id está chegando
    $postId = mysql_real_escape_string($_POST['post_id']);
    
    if (empty($postId)) {
        die('Post ID está vazio');
    }

    // Debug: Verifique se o email do usuário está na sessão
    if (empty($usuarioEmail)) {
        die('Email do usuário não está na sessão');
    }

    // Debug: Exibe os valores para verificar se estão corretos
    echo "Usuário: " . $usuarioEmail . "<br>";
    echo "Post ID: " . $postId . "<br>";

    // Deleta o post apenas se for do usuário logado
    $deleteQuery = "DELETE FROM tb_posts WHERE Email = '$usuarioEmail' AND Id_Posts = '$postId'";
    $deleteResult = mysql_query($deleteQuery);

    // Debug: Verifique se a query foi executada
    if ($deleteResult) {
        echo "Post deletado com sucesso!";
        header("Location: mainperfil.php");
        exit();
    } else {
        die('Erro ao deletar o post: ' . mysql_error());
    }
} else {
    die('Post ID não foi enviado');
}
?>
