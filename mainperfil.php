<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil </title>
    <link rel="stylesheet" href="mainperfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>
<?php
session_start();
$email_usuario = $_SESSION['email_usuario'];
include 'conexao.php';
if (isset($_GET['email'])) {
    $emailperfil = $_GET['email'];
} else {
    $emailperfil = ''; // Se não foi passado, inicializa como vazio
}

// Consulta para verificar se o usuário atual está seguindo o perfil
$seguindoQuery = "SELECT following FROM seguindo WHERE follower = '$email_usuario'";
$seguindoResult = mysql_query($seguindoQuery);
$seguindoEmails = [];

// Armazena os emails dos seguidores em um array
while ($row = mysql_fetch_assoc($seguindoResult)) {
    $seguindoEmails[] = $row['following'];
}

// Verifica se o perfil atual está na lista de seguidos
$seguindo = in_array($emailperfil, $seguindoEmails); 
$buttonText = $seguindo ? '<i class="fa-solid fa-xmark"></i> Deixar de Seguir' : '<i class="fa-solid fa-check"></i> Seguir';
$buttonClass = $seguindo ? 'unfollow-btn' : 'follow-btn';

// Consulta para buscar os dados do perfil
$sql = "SELECT * FROM tb_contas WHERE Email = '$emailperfil'";
$resultado = mysql_query($sql);

if ($resultado && mysql_num_rows($resultado) > 0) {
    while ($row = mysql_fetch_assoc($resultado)) {
        // Sanitiza os dados recebidos do banco
        $nomeUsuario = htmlspecialchars($row['Nome']);
        $cidadeUsuario = htmlspecialchars($row['Cidade']);
        $instaUsuario = htmlspecialchars($row['Instagram']);
        $faceUsuario = htmlspecialchars($row['Facebook']);
        $linkedinUsuario = htmlspecialchars($row['Linkedin']);
        $seguidoresUsuario = htmlspecialchars($row['Seguidores']);
        $sobreUsuario = htmlspecialchars($row['Sobre']);
        $emaildousuario=htmlspecialchars($row['Email']);
        
        // Converte a imagem do contato para base64
        if (!empty($row['Imagem'])) {
            $imagemSrc = 'data:image/jpeg;base64,' . base64_encode($row['Imagem']);
        } else {
            $imagemSrc = './Images/noPhoto.png'; // Imagem padrão
        }
    }
} else {
    // Se não encontrou, busca os dados do usuário atual
    $sql = "SELECT * FROM tb_contas WHERE Email = '$email_usuario'";
    $resultado = mysql_query($sql);
    
    if ($resultado && mysql_num_rows($resultado) > 0) {
        while ($row = mysql_fetch_assoc($resultado)) {
            // Sanitiza os dados recebidos do banco
            $nomeUsuario = htmlspecialchars($row['Nome']);
            $cidadeUsuario = htmlspecialchars($row['Cidade']);
            $instaUsuario = htmlspecialchars($row['Instagram']);
            $faceUsuario = htmlspecialchars($row['Facebook']);
            $linkedinUsuario = htmlspecialchars($row['Linkedin']);
            $seguidoresUsuario = htmlspecialchars($row['Seguidores']);
            $sobreUsuario = htmlspecialchars($row['Sobre']);
            $emaildousuario=htmlspecialchars($row['Email']);
            
            // Converte a imagem do contato para base64
            if (!empty($row['Imagem'])) {
                $imagemSrc = 'data:image/jpeg;base64,' . base64_encode($row['Imagem']);
            } else {
                $imagemSrc = './Images/noPhoto.png'; // Imagem padrão
            }
        }
    }
}
if($emailperfil == ''){
$seguidoresQuery = "SELECT follower FROM seguindo WHERE following = '$email_usuario'";
$seguidoresResult = mysql_query($seguidoresQuery);
$seguidoresEmails = [];
}else{
    $seguidoresQuery = "SELECT follower FROM seguindo WHERE following = '$emailperfil'";
    $seguidoresResult = mysql_query($seguidoresQuery);
    $seguidoresEmails = [];
}
// Armazena os e-mails dos seguidores em um array
while ($row = mysql_fetch_assoc($seguidoresResult)) {
    $seguidoresEmails[] = $row['follower'];
}

// Agora, busque as imagens de cada seguidor
$seguidoresFotos = [];
foreach ($seguidoresEmails as $emailSeguidor) {
    $fotoQuery = "SELECT Imagem FROM tb_contas WHERE Email = '$emailSeguidor'";
    $fotoResult = mysql_query($fotoQuery);

    if ($fotoResult && mysql_num_rows($fotoResult) > 0) {
        $fotoRow = mysql_fetch_assoc($fotoResult);
        if (!empty($fotoRow['Imagem'])) {
            $imagemSeguidor = 'data:image/jpeg;base64,' . base64_encode($fotoRow['Imagem']);
        } else {
            $imagemSeguidor = ''; // Imagem padrão se não houver foto
        }
        $seguidoresFotos[] = $imagemSeguidor;
    }
}
?>
    <div class="container">
        <div class="profile-card">
            <div class="header">
                <div class="profile-info">
                    <h1 class="name"><?php echo htmlspecialchars($nomeUsuario); ?> </h1>
                    <p class="username"><?php echo htmlspecialchars($cidadeUsuario); ?></p>
                    <p class="bio"><?php echo htmlspecialchars($sobreUsuario); ?></p>
                    <a href="seguidores.php?email=<?php echo urlencode($emaildousuario); ?>">
                    <h2>Seguidores</h2>
                    </a>
                    <div class="followers">
    <?php if ($seguidoresFotos != 0){
    // Limitar a exibição a dois seguidores
    $contador = 0;
    foreach ($seguidoresFotos as $index => $foto) {
        if ($contador >= 2) {
            break; // Para de exibir após dois seguidores
        }

        echo '<img src="' . $foto . '" alt="Seguidor ' . ($index + 1) . '">';
        $contador++;
    }}else{   
                echo'<img src="silueta.jpg" alt="Seguidor 1">
                    <img src="silueta.jpg" alt="Seguidor 2">';   
    }


    ?>
    <h2>
        <span id="follower-count-<?php echo htmlspecialchars($emailperfil); ?>">
            <?php echo htmlspecialchars($seguidoresUsuario); ?>
        </span>
    </h2>
</div>

                </div>
                <div class="profile-pic"><?php
               echo' <img src= "' . $imagemSrc . '"   alt="Foto de Perfil">'; ?>
                </div>
            </div>
            <?php
                if ($emailperfil == $email_usuario || $emailperfil == ''  ){
           echo ' <div class="edit-profile">
            <a href="cadperfil.html" class="edit-perfil">
             <i class="fas fa-edit"></i> Editar Perfil
                            </a>';
                }
                else{
                    echo ' <div class="edit-profile">
                    <button class="' . $buttonClass . '" data-email="' . $emailperfil . '" onclick="toggleFollow(this)">' . $buttonText . '</button>     
                    ';
                                    
                }
                          
                
             echo' <ul class="icones"><li> <a href="<?php echo htmlspecialchars($instaUsuario); ?>" class="instagram-link" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="<?php echo htmlspecialchars($linkedinUsuario); ?>" class="instagram-link" target="_blank">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
                <a href="<?php echo htmlspecialchars($faceUsuario); ?>" class="instagram-link" target="_blank">
                    <i class="fa-brands fa-facebook"></i>';
                    if ($emailperfil == $email_usuario|| $emailperfil == '') {}
                    else{
                        echo '<a href="batepapo.php?email=' . urlencode($emailperfil) . '" class="instagram-link" >
                                  <i class="fa-solid fa-message"></i></a>';
                    }
                    ?>
                </a>    
                </a></li></ul>
            </div>
            <div class="menu">
                <ul>
                    <span>Publicações</span>
                   
                </ul>
            </div>
            <br>
            <?php
date_default_timezone_set('America/Sao_Paulo');

$query = "SELECT * FROM republicar";

$resultado = mysql_query($query);

if ($resultado && mysql_num_rows($resultado) > 0) {
    while ($row = mysql_fetch_assoc($resultado)) {
    $hora = htmlspecialchars($row['Hora']);
    $dia = htmlspecialchars($row['Dia']);
    $autimg = htmlspecialchars($row['Imgautor']);
    $autor = htmlspecialchars($row['usuario_email']);
    $id = htmlspecialchars($row['Id_Posts']);
    $usuarioEmail = $_SESSION['email_usuario'];

    $query = "INSERT INTO tb_posts (Autor, Conteudo, Dia, Email, Hora, Imagem, AutImg, likes) 
    SELECT '$autor', Conteudo, '$dia', '$usuarioEmail', '$hora', Imagem, '$autimg', 0 
    FROM tb_posts 
    WHERE Id_Posts = $id";

    $result = mysql_query($query) or die('Erro na consulta: ' . mysql_error());
    $query = "DELETE FROM republicar;";
    $result = mysql_query($query) or die('Erro na consulta: ' . mysql_error());
    }}

if($emailperfil == ''){
    $query = "SELECT * FROM tb_posts 
WHERE Email = '$email_usuario' ORDER BY Dia DESC, Hora DESC"; 
}
else{
$query = "SELECT * FROM tb_posts WHERE Email = '$emailperfil' ORDER BY Dia DESC, Hora DESC";
}

$result = mysql_query($query);

if ($result && mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        // Formatar a data e hora do post para exibição
        $dataHoraUTC = (new DateTime($row['Dia'] . ' ' . $row['Hora'], new DateTimeZone('America/Sao_Paulo')))->format('H:i d/m/Y');

        // Estrutura HTML de cada postagem dentro de um contêiner individual
        echo '<div class="postContainer">';

        // Informações do usuário que fez o post
        echo '<div class="infoUserPost">
                <div class="imgUserPost"><img src="' . $row['AutImg'] . '" alt="Profile Picture"></div>
                <div class="nameAndHour">
                    <strong>' . htmlspecialchars($row['Autor']) . '</strong><p>' . $dataHoraUTC . '</p>
                </div>
              </div>';

        // Conteúdo do post (caso exista)
        if (!empty($row['Conteudo'])) {
            echo '<div id="contp"><p>' . htmlspecialchars($row['Conteudo']) . '</p></div>';
        }

        // Imagem do post (caso exista)
        if (!empty($row['Imagem'])) {
            echo '<div class="postImage"><img src="' . $row['Imagem'] . '" alt="Post Image"></div>';
        }

        // Botões de ação para Curtir, Republicar e, se o post for do usuário, Excluir
        echo '<div class="actionBtnPost">
                <button type="button" onclick="toggleLike(this, ' . $row['Id_Posts'] . ')" class="filesPost like">
                    <img src="./assets/heart.png" alt="Curtir"><p>Curtir</p>
                    <span class="likeCount">' . $row['likes'] . '</span>
                </button>
                <button type="button" onclick="toggleRepublicar(this, '. $row['Id_Posts'] . ')" class="filesPost share">
            <img src="./assets/share.svg" alt="Compartilhar">
            <p>Republicar</p>
        </button>';

        // Condição para exibir o botão de excluir apenas para o dono do post
        if ($emailperfil == $email_usuario || $emailperfil == '') {
            echo '<form action="toggle_delete.php" method="POST">
                    <input type="hidden" name="post_id" value="' . $row['Id_Posts'] . '">
                    <button type="submit" class="filesPost excluir">
                        <img src="./assets/lixeira.png" alt="Excluir"><p>Excluir</p>
                    </button>
                  </form>';
        }

        echo '</div>';  // Fechando div de ações
        echo '</div>';  // Fechando div de cada postagem
        echo '<br><hr><br>';  // Linha divisória entre postagens
    }
} else {
    echo '<p>Nenhum post encontrado.</p>';
}
?>

</div>
</div>
</div>




            </div>
        </div>
       
    </div>
    <script>
    function toggleLike(button, postId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'toggle_like.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Atualiza a contagem de likes
                const likeCountElement = button.querySelector('.likeCount');
                likeCountElement.textContent = response.newLikeCount;

                // Alterna o ícone e texto de Curtir/Descurtir
                const img = button.querySelector('img');
                const p = button.querySelector('p');
                if (response.liked) {
                    img.src = './assets/heart-filled.png';  // Ícone de curtida
                    p.textContent = 'Descurtir';
                } else {
                    img.src = './assets/heart.png';  // Ícone de não curtida
                    p.textContent = 'Curtir';
                }
            }
        }
    };

    // Envia o ID do post
    xhr.send('post_id=' + postId);
}

function toggleRepublicar(button, postId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'toggle_republicar.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Republicado com sucesso!!!");

                // Recarrega a página após republicar
                location.reload();
            } else {
                console.error(response.message);
            }
        }
    };

    xhr.send('post_id=' + postId);
}

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
