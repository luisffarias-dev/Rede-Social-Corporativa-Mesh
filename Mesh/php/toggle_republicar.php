<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexao.php';
session_start();

$usuarioEmail = $_SESSION['email_usuario'];

$query = "SELECT Nome, Imagem FROM tb_contas WHERE Email = '$usuarioEmail'";
$result = mysql_query($query);

if (!$result) {
    die('Erro na consulta: ' . mysql_error());
}

if ($row = mysql_fetch_assoc($result)) {
    $nome_usuario = $row['Nome'];
    $imagem_usuario = $row['Imagem'];

    if (!empty($imagem_usuario)) {
        $imagem_usuario = 'data:image/jpeg;base64,' . base64_encode($imagem_usuario);
    } else {
        $imagem_usuario = './Images/noPhoto.png';
    }
} else {
    $nome_usuario = "Usuário não encontrado";
    $imagem_usuario = './Images/noPhoto.png';
}

date_default_timezone_set('America/Sao_Paulo');

$data = date('Y-m-d');
$hora = date('H:i:s');

if (isset($_POST['post_id'])) {
    $postId = mysql_real_escape_string($_POST['post_id']);
    
    // Verifica se o post já foi republicado
    $checkQuery = "SELECT * FROM republicar WHERE usuario_email = '$nome_usuario' AND Id_Posts = '$postId'";
    $checkResult = mysql_query($checkQuery);

    if (mysql_num_rows($checkResult) === 0) {
        // Se não estiver republicado, insere
        $insertQuery = "INSERT INTO republicar (usuario_email, Id_Posts, imgautor, Dia, Hora) VALUES ('$nome_usuario', '$postId', '$imagem_usuario', '$data', '$hora')";
        mysql_query($insertQuery);
        $republicar = true;
    } else {
        // Se já estiver republicado, remove
        $deleteQuery = "DELETE FROM republicar WHERE usuario_email = '$nome_usuario' AND Id_Posts = '$postId'";
        mysql_query($deleteQuery);
        $republicar = false;
    }

    // Busca os detalhes do post para criar o HTML
    $postQuery = "SELECT * FROM tb_posts WHERE Id_Posts = '$postId'";
    $postResult = mysql_query($postQuery);
    
    if ($postResult && mysql_num_rows($postResult) > 0) {
        $post = mysql_fetch_assoc($postResult);

        $hora = htmlspecialchars($post['Hora']);
        $dataantiga = htmlspecialchars($post['Dia']);
        $autor = htmlspecialchars($post['Autor']);
        $conteudo = htmlspecialchars($post['Conteudo']);
        $img = htmlspecialchars($post['Imagem']);
        $autimg = htmlspecialchars($post['AutImg']);
        $like = htmlspecialchars($post['likes']);
        

        // Formata a data e hora
        $dataHora = new DateTime($dataantiga . ' ' . $hora, new DateTimeZone('America/Sao_Paulo'));
        $dataHoraUTC = $dataHora->format('H:i d/m/Y');

        // Cria o HTML do novo post
        $postHtml = '
    <div class="infoUserPost">
    <div class="imgname">
            <div class="imgUserPost">
                <img src="' . $imagem_usuario . '" alt="Profile Picture">
            </div>
            <strong>' . $nome_usuario . '</strong>
    </div>
        
            <p>' . $dataHoraUTC . '</p>
        </div>
    </div>';



if (!empty($conteudo)) {
    $postHtml .= '<div id="contp"><p>' . $conteudo . '</p></div>';
}

if (!empty($img)) {
    $postHtml .= '<div class="postImage"><img src="' . $img . '" alt="Post Image"></div>';
}

$postHtml .= '

<div class="actionBtnPost">
        <button type="button" onclick="toggleLike(this, ' . $postId . ')" class="filesPost like">
            <img src="./assets/heart.png" alt="Curtir">
            <p>Curtir</p>
            <span class="likeCount">' . $like . '</span>
        </button>
        <button type="button" onclick="toggleComentar(this, '. $postId . ')" class="filesPost comment">
            <img src="./assets/comment.png" alt="Comentar">
            <p>Comentar</p>
        </button>
        <button type="button" onclick="toggleRepublicar(this, '. $postId .')" class="filesPost share">
            <img src="./assets/share.svg" alt="Compartilhar">
            <p>Republicar</p>
        </button>
    </div>
    <br>
<div class="linha"></div>';



        // Retorna a resposta em JSON com o HTML do post
        echo json_encode([
            'success' => true,
            'republicar' => $republicar,
            'postHtml' => $postHtml
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Post não encontrado.']);
    }
}
?>
