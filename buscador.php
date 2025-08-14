<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Sugestões</title>
    <link rel="stylesheet" href="buscador.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php
session_start(); // Inicie a sessão

// E-mail do usuário logado
include 'conexao.php'; // Inclua seu arquivo de conexão ao banco de dados

// Verifique se o e-mail do usuário está definido na sessão
if (!isset($_SESSION['email_usuario'])) {
    die("Erro: Usuário não está logado.");
}
$usuarioEmail = $_SESSION['email_usuario'];

// Inicializa a variável para armazenar a busca
$searchTerm = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = mysql_real_escape_string($_POST['search']);
    $query = "SELECT Nome, Email, Imagem, seguidores FROM tb_contas WHERE Nome LIKE '%$searchTerm%' LIMIT 15";
    $result = mysql_query($query);
} else {
    $query = "SELECT Nome, Email, Imagem, seguidores FROM tb_contas LIMIT 15";
    $result = mysql_query($query);
}

// Consulta para verificar quem o usuário está seguindo
$seguindoQuery = "SELECT following FROM seguindo WHERE follower = '$usuarioEmail'";
$seguindoResult = mysql_query($seguindoQuery);
$seguindoEmails = [];
while ($row = mysql_fetch_assoc($seguindoResult)) {
    $seguindoEmails[] = $row['following'];
}
?>
<br>
<div class="container">
    <div class="search-bar">
        <form class="form-container" role="search" method="Post" action="buscador.php">
            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" name="search">
        </form>
    </div>

    <div class="suggestions">
        <br>
        <center> <h2>Sugestões para seguir</h2> </center>
        <ul>
            <?php
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    // Verifica se a imagem está disponível
                    if (!empty($row['Imagem'])) {
                        $imagemData = base64_encode($row['Imagem']);
                        $imagemSrc = 'data:image/jpeg;base64,' . $imagemData;
                    } else {
                        // Se não houver imagem, usa uma imagem padrão
                        $imagemSrc = './Images/noPhoto.png';
                    }
                    // Cria o link para a página de perfil
                   

                    // Verifica se o usuário está seguindo
                    $seguindo = in_array($row['Email'], $seguindoEmails);

                    // Define o texto do botão e a classe
                    $buttonText = $seguindo ? '<i class="fa-solid fa-xmark"></i> Deixar de Seguir' : '<i class="fa-solid fa-check"></i> Seguir';
                    $buttonClass = $seguindo ? 'unfollow-btn' : 'follow-btn';

                    // Exibe o perfil com o link na imagem, o botão de seguir, e o número de seguidores
                    echo '<li>
                            <div class="profile-info">
                                <a href="mainperfil.php?email=' . urlencode($row['Email']) . '">
                                    <img src="' . $imagemSrc . '" alt="Imagem de perfil" class="profile-pic">
                                </a>
                                <div class="profile-details">
                                    <span class="profile-name">' . htmlspecialchars($row['Nome']) . '</span>
                                    <span class="followers">Seguidores: <span id="follower-count-' . $row['Email'] . '">' . htmlspecialchars($row['seguidores']) . '</span></span>
                                </div>
                            </div>
                            <button class="' . $buttonClass . '" data-email="' . $row['Email'] . '" onclick="toggleFollow(this)">' . $buttonText . '</button>
                          </li>';
                }
            } else {
                echo '<li>Nenhum contato encontrado</li>';
            }
            ?>
        </ul>
    </div>
</div>
<div class="card-footer"></div>

<script>
    function toggleFollow(button) {
        const email = button.getAttribute('data-email');
        const isFollowing = button.classList.contains('unfollow-btn'); // Verifica se já está seguindo
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'toggle_follow.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                
                // Atualiza o texto do botão e o estado (seguidor/seguindo)
                if (response.success) {
                    const followerCountElement = document.getElementById('follower-count-' + email);
                    followerCountElement.textContent = response.newFollowerCount;

                    if (isFollowing) {
                        button.innerHTML = '<i class="fa-solid fa-check"></i> Seguir';
                        button.classList.remove('unfollow-btn');
                        button.classList.add('follow-btn');
                    } else {
                        button.innerHTML = '<i class="fa-solid fa-xmark"></i> Deixar de Seguir';
                        button.classList.remove('follow-btn');
                        button.classList.add('unfollow-btn');
                    }
                }
            }
        };

        // Envia a requisição com o estado atual de seguir/desseguir
        xhr.send('email=' + email + '&action=' + (isFollowing ? 'unfollow' : 'follow'));
    }
</script>
</body>
</html>
