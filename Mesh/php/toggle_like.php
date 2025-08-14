<?php
include 'conexao.php';
session_start();

$usuarioEmail = $_SESSION['email_usuario'];

if (isset($_POST['post_id'])) {
    $postId = mysql_real_escape_string($_POST['post_id']);
    
    // Verifica se o usuário já curtiu o post
    $checkQuery = "SELECT * FROM likes WHERE usuario_email = '$usuarioEmail' AND Id_Posts = '$postId'";
    $checkResult = mysql_query($checkQuery);

    if (mysql_num_rows($checkResult) === 0) {
        // Insere o like
        $insertQuery = "INSERT INTO likes (usuario_email, Id_Posts) VALUES ('$usuarioEmail', '$postId')";
        mysql_query($insertQuery);

        // Atualiza o número de likes
        $updateQuery = "UPDATE tb_posts SET likes = likes + 1 WHERE Id_Posts = '$postId'";
        mysql_query($updateQuery);

        $liked = true;
    } else {
        // Remove o like
        $deleteQuery = "DELETE FROM likes WHERE usuario_email = '$usuarioEmail' AND Id_Posts = '$postId'";
        mysql_query($deleteQuery);

        // Atualiza o número de likes
        $updateQuery = "UPDATE tb_posts SET likes = likes - 1 WHERE Id_Posts = '$postId'";
        mysql_query($updateQuery);

        $liked = false;
    }

    // Pega o novo número de likes
    $queryLikes = "SELECT likes FROM tb_posts WHERE Id_Posts = '$postId'";
$resultLikes = mysql_query($queryLikes);
$row = mysql_fetch_assoc($resultLikes);

echo json_encode([
    'success' => true,
    'newLikeCount' => $row['likes'],
    'liked' => $liked
]);

}
?>
