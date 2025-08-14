<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Seguidores e Seguindo</title>
    <link rel="stylesheet" href="seguidores.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php
session_start();

$email_usuario = $_SESSION['email_usuario'];
include 'conexao.php';

// Verifica se o perfil de email foi passado na URL
if (isset($_GET['email'])) {
    $emailperfil = $_GET['email'];
} else {
    $emailperfil = ''; // Se não foi passado, inicializa como vazio
}

// Configura o estado do botão de "Seguindo" ou "Seguidores"
$isFollowingTab = isset($_POST['toggleTab']) && $_POST['toggleTab'] === 'seguindo';

// Termo de busca (se disponível)
$searchTerm = '';
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = mysql_real_escape_string($_POST['search']);
}

// Consulta SQL com base no botão ativo
if ($isFollowingTab) {
    // Consulta para "Estou Seguindo"
    // Consulta para "Meus Seguidores"
    $sql = "SELECT follower FROM seguindo WHERE following = '$emailperfil'";
    $seguindoResult = mysql_query($sql);
    $seguidoresEmails = [];

    // Armazena os emails dos seguidores
    while ($row = mysql_fetch_assoc($seguindoResult)) {
        $seguidoresEmails[] = $row['follower'];
    }
    
    // Consulta para buscar informações dos seguidores
    $query = "SELECT Nome, Email, Imagem, seguidores 
              FROM tb_contas 
              WHERE Email IN ('" . implode("','", $seguidoresEmails) . "')";
    
} else {
    $sql = "SELECT following FROM seguindo WHERE follower = '$emailperfil'";
    $seguindoResult = mysql_query($sql);
    $seguindoEmails = [];

    // Armazena os emails que o usuário está seguindo
    while ($row = mysql_fetch_assoc($seguindoResult)) {
        $seguindoEmails[] = $row['following'];
    }
    
    // Consulta para buscar informações dos usuários seguidos
    $query = "SELECT Nome, Email, Imagem, seguidores 
              FROM tb_contas 
              WHERE Email IN ('" . implode("','", $seguindoEmails) . "')";
}

// Executa a consulta final
$result = mysql_query($query);

// Armazena emails que o usuário está seguindo para os botões de seguir/desseguir
$seguindoQuery = "SELECT following FROM seguindo WHERE follower = '$email_usuario'";
$seguindoResult = mysql_query($seguindoQuery);
$seguindoEmails = [];
while ($row = mysql_fetch_assoc($seguindoResult)) {
    $seguindoEmails[] = $row['following'];
}
?>


<br>
<div class="container">
<div class="voltar-btn">
    <a href="mainperfil.php?email=<?php echo urlencode($emailperfil); ?>">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>

    <div class="search-bar">
        <form class="form-container" role="search" method="post" action="">
            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
            <input type="hidden" name="toggleTab" value="<?= $isFollowingTab ? 'seguindo' : 'seguidores' ?>">
        </form>
    </div>
    
    <div class="suggestions">
        <center>
            <h2>
                <form method="post" action="">
                    <button class="toggle-button" name="toggleTab" value="<?= $isFollowingTab ? 'seguidores' : 'seguindo' ?>">
                        <?= $isFollowingTab ? 'Seguidores' : 'Seguindo' ?>
                    </button>
                </form>
            </h2>
        </center>
        <ul>
            <?php
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_assoc($result)) {
                    $imagemSrc = !empty($row['Imagem']) ? 'data:image/jpeg;base64,' . base64_encode($row['Imagem']) : './Images/noPhoto.png';
                    $seguindo = in_array($row['Email'], $seguindoEmails);
                    $buttonText = $seguindo ? '<i class="fa-solid fa-xmark"></i> Deixar de Seguir' : '<i class="fa-solid fa-check"></i> Seguir';
                    $buttonClass = $seguindo ? 'unfollow-btn' : 'follow-btn';

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
                echo '<div class="no-results-message">Nenhuma conexão encontrada</div>';
            }
            ?>
        </ul>
    </div>
</div>
<div class="card-footer"></div>

<script>
    function toggleFollow(button) {
        const email = button.getAttribute('data-email');
        const isFollowing = button.classList.contains('unfollow-btn');
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'toggle_follow.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                
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

        xhr.send('email=' + email + '&action=' + (isFollowing ? 'unfollow' : 'follow'));
    }
    document.addEventListener('DOMContentLoaded', function () {
        const meusSeguidoresBtn = document.getElementById('meus-seguidores-btn');
        const estouSeguindoBtn = document.getElementById('estou-seguindo-btn');

        // Função para alternar a cor ativa entre os botões
        function toggleActiveButton(activeButton, inactiveButton) {
            activeButton.classList.add('active-button');
            inactiveButton.classList.remove('active-button');
        }

        // Inicializa o estado com "Meus Seguidores" ativo
        toggleActiveButton(meusSeguidoresBtn, estouSeguindoBtn);

        // Adiciona os event listeners para alternar entre os botões
        meusSeguidoresBtn.addEventListener('click', function () {
            toggleActiveButton(meusSeguidoresBtn, estouSeguindoBtn);
        });

        estouSeguindoBtn.addEventListener('click', function () {
            toggleActiveButton(estouSeguindoBtn, meusSeguidoresBtn);
        });
    });
</script>
</body> 
</html>
