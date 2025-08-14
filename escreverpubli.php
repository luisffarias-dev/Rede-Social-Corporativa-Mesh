<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="escreverpubli.css">
    <title>Mesh</title>
</head>
<body bgcolor='MidnightBlue' text='white'>

<div id="allcont">
<center>
    <div class="newPost">
        <div class="infoUser">
            <div class="imgUser">
                <?php include 'sidebar_dados.php'?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($imagem_usuario); ?>" id="user_avatar" alt="avatar">
            </div>
            <strong><?php echo $nome_usuario; ?></strong>
        </div>
        <form name="form1" method="post" action="publicar.php" enctype="multipart/form-data" class="formPost" id="formPost">
            <textarea name="textarea" placeholder="Deseja compartilhar algo hoje?" id="textarea"></textarea>
            <?php echo '<input type="hidden" name="imgSelecionada" value="' . htmlspecialchars($imagem_usuario) . '">';?>
            <div class="iconsAndButton">
                <div class="icons">
                    <div class="midia">
                        <label for="file"><img src="./assets/img.svg" alt="Adicionar midia"></label>
                        <h4></h4>
                        <input id="file" type="file" name="file">
                    </div>
                </div>
                <input type="submit" value="Publicar" id="botao" class="btnSubmitForm">
            </div>
        </form>
    </div>
    <ul class="posts" id="posts"></ul>
</center>
<div id="post">
<?php
// Definindo o fuso horário
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


$query = "SELECT * FROM tb_posts ORDER BY Dia DESC, Hora DESC";

$result = mysql_query($query);

if ($result && mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        // Extrai os dados da linha do banco de dados
        $hora = htmlspecialchars($row['Hora']);
        $dataantiga = htmlspecialchars($row['Dia']);
        $autor = htmlspecialchars($row['Autor']);
        $conteudo = htmlspecialchars($row['Conteudo']);
        $img = htmlspecialchars($row['Imagem']);
        $autimg = htmlspecialchars($row['AutImg']);
        $like = htmlspecialchars($row['likes']);
        $emailautor=htmlspecialchars($row['Email']);

        // Converte a data e hora para UTC
        $dataHora = new DateTime($dataantiga . ' ' . $hora, new DateTimeZone('America/Sao_Paulo'));
        
        $dataHoraUTC = $dataHora->format('H:i d/m/Y');

        // Exibe o post formatado
        
        echo '<div class="infoUserPost">

                <div class="imgname">
                <a href="mainperfil.php?email=' . urlencode($emailautor) . '"> 
                <div class="imgUserPost">
                <img src="' . $autimg . '" alt="Profile Picture">
                </div>
                </a>
                <strong>' . $autor . '</strong>
                </div>
                <p>' . $dataHoraUTC . '</p>
            </div>';
        if (!empty($conteudo)) {
            echo '<div id="contp">
                    <p>' . $conteudo . '</p>
                </div>';
        }

        if (!empty($img)) {
            echo '<div class="postImage">
                    
                    <img src="' . $img . '" alt="Post Image">
                </div>';
        }

        echo '
    <div class="actionBtnPost">
        <button type="button" onclick="toggleLike(this, ' . $row['Id_Posts'] . ')" class="filesPost like">
            <img src="./assets/heart.png" alt="Curtir">
            <p>Curtir</p>
            <span class="likeCount">' . $like . '</span>
        </button>
        <button type="button" onclick="toggleComentar(this, '. $row['Id_Posts'] . ')" class="filesPost comment">
            <img src="./assets/comment.png" alt="Comentar">
            <p>Comentar</p>
        </button>
        <button type="button" onclick="toggleRepublicar(this, '. $row['Id_Posts'] . ')" class="filesPost share">
            <img src="./assets/share.svg" alt="Compartilhar">
            <p>Republicar</p>
        </button>
    </div>
    <br>
    <div class="linha"></div>
';


    }
} else {
    echo '<p>Nenhum post encontrado.</p>';
}
?>
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
                const postsContainer = document.getElementById('post'); // Usando o ID 'post'
                
                // Insere o HTML do post diretamente na div existente
                postsContainer.insertAdjacentHTML('afterbegin', response.postHtml);
                
                alert("Republicado com sucesso!!!");
            } else {
                console.error(response.message);
            }
        }
    };

    xhr.send('post_id=' + postId);
}
function toggleComentar(button, postId) {
    // Redireciona para a página comentar.php com o id do post na URL
    window.location.href = 'toggle_comentar.php?idPost=' + postId;
}

</script>

</body>
</html>