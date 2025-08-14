<?php
include 'conexao.php'; // Inclua seu arquivo de conexão ao banco de dados

session_start(); // Inicie a sessão para acessar o e-mail do usuário logado

$usuarioEmail = $_SESSION['email_usuario']; // Supondo que o e-mail do usuário logado está na sessão

if (isset($_POST['email']) && isset($_POST['action'])) {
    $seguidoEmail = mysql_real_escape_string($_POST['email']);
    $action = $_POST['action'];

    if ($action === 'follow') {
        // Verifica se o usuário já está seguindo
        $checkQuery = "SELECT * FROM seguindo WHERE following = '$usuarioEmail' AND follower = '$seguidoEmail'";
        $checkResult = mysql_query($checkQuery);

        if (mysql_num_rows($checkResult) === 0) {
            // Insere na tabela de seguindo
            $insertQuery = "INSERT INTO seguindo (follower, following) VALUES ('$usuarioEmail', '$seguidoEmail')";
            mysql_query($insertQuery);

            // Atualiza o número de seguidores na tabela tb_contas
            $updateQuery = "UPDATE tb_contas SET seguidores = seguidores + 1 WHERE Email = '$seguidoEmail'";
            mysql_query($updateQuery);
        }
    } else {
        // Remove da tabela de seguindo
        $deleteQuery = "DELETE FROM seguindo WHERE follower = '$usuarioEmail' AND following = '$seguidoEmail'";
        mysql_query($deleteQuery);

        // Atualiza o número de seguidores na tabela tb_contas
        $updateQuery = "UPDATE tb_contas SET seguidores = seguidores - 1 WHERE Email = '$seguidoEmail'";
        mysql_query($updateQuery);
    }

    // Pega o novo número de seguidores
    $queryFollower = "SELECT seguidores FROM tb_contas WHERE Email = '$seguidoEmail'";
    $resultFollower = mysql_query($queryFollower);
    $row = mysql_fetch_assoc($resultFollower);

    // Envia a resposta como JSON com o novo número de seguidores
    echo json_encode([
        'success' => true,
        'newFollowerCount' => $row['seguidores']
    ]);
}
?>
