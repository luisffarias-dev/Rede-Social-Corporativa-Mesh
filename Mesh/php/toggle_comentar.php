<?php
$idPost = isset($_GET['idPost']) ? intval($_GET['idPost']) : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;1,700&display=swap" rel="stylesheet">
     <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="comentario.css">
    <title>Mesh</title>
</head>
<body bgcolor='MidnightBlue' text='white'>
<header>
<div id="back">
    <a href="chamar_publis.html" target="_top">
    <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>
    <h6>COMENTARIOS</h6>
</header>
<div id="allcont">
<center>
    <div id="out">
    <div class="newPost"><?php include 'sidebar_dados.php'?>
        
        <form name="form1" method="post" action="comentar.php" enctype="multipart/form-data" class="formPost" id="formPost">
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
                <input type="submit" value="Comentar" id="botao" class="btnSubmitForm">
                <?php echo'<input type="hidden" value="' . $idPost . '" name="id"> '?>
            </div>
        </form>
    </div>
    </div>
    <ul class="posts" id="posts"></ul>
</center>
<div id="postback">
<div id="post">
<?php

// Definindo o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Consulta SQL para buscar comentários relacionados ao post
$query= "SELECT * FROM tb_comments WHERE Id_Posts = $idPost ORDER BY Dia DESC, Hora DESC";
$result = mysql_query($query);

if ($result && mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
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
        <button type="button" onclick="toggleLike(this, ' . $row['Id_Comments'] . ')" class="filesPost like">
            <img src="./assets/heart.png" alt="Curtir">
            <p>Curtir</p>
            <span class="likeCount">' . $like . '</span>
        </button>
        </div>
    <br>
    <div class="linha"></div>
';


    }
} else {
    echo '<p>Compartilhe as suas ideias por meio dos comentários!</p>';
}
?>
</div>
</div>
</div>
<script>
    function toggleLike(button, postId) {
    const tipo = "comment";  // Defina a string que deseja enviar

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

    // Envia o ID do post e a string 'mensagem' pelo método POST
    xhr.send('post_id=' + postId + '&tipo=' + encodeURIComponent(tipo));
}

</script>

</body>
</html>