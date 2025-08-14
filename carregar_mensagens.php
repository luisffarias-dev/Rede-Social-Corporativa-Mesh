<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$email_usuario = $_SESSION['email_usuario'];
$email_Selecionado = $_GET['emailSelecionado'];

// Inclua seu arquivo de conexão ao banco de dados
include 'conexao.php';

// Obtendo a imagem do usuário logado
$queryUsuario = "SELECT Imagem FROM tb_contas WHERE Email = '$email_usuario'";
$resultUsuario = mysql_query($queryUsuario);
$imagem_usuario = './Images/noPhoto.png'; // Imagem padrão

if ($resultUsuario && mysql_num_rows($resultUsuario) > 0) {
    $rowUsuario = mysql_fetch_assoc($resultUsuario);
    if (!empty($rowUsuario['Imagem'])) {
        $imagem_usuario = 'data:image/jpeg;base64,' . base64_encode($rowUsuario['Imagem']);
    }
}

// Obtendo a imagem do contato
$queryContato = "SELECT Imagem FROM tb_contas WHERE Email = '$email_Selecionado'";
$resultContato = mysql_query($queryContato);
$imagemSrc = './Images/noPhoto.png'; // Imagem padrão

if ($resultContato && mysql_num_rows($resultContato) > 0) {
    $rowContato = mysql_fetch_assoc($resultContato);
    if (!empty($rowContato['Imagem'])) {
        $imagemSrc = 'data:image/jpeg;base64,' . base64_encode($rowContato['Imagem']);
    }
}

$sql = "SELECT Mensagem, Dia, Hora, Autor 
        FROM tb_chat 
        WHERE (Autor = '$email_usuario' AND Destinatario = '$email_Selecionado') 
        OR (Autor = '$email_Selecionado' AND Destinatario = '$email_usuario') 
        ORDER BY Dia, Hora";

$result = mysql_query($sql);  
if ($result && mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $hora = htmlspecialchars($row['Hora']);
        $data = htmlspecialchars($row['Dia']);
        $mensagem = htmlspecialchars($row['Mensagem']);
        $autor = htmlspecialchars($row['Autor']);

        $dataHora = new DateTime($data . ' ' . $hora, new DateTimeZone('America/Sao_Paulo'));
        
        $dataHoraUTC = $dataHora->format('H:i d/m/Y');

        // Verifica se a mensagem foi enviada pelo usuário ou recebida
        if ($autor === $email_usuario) {
            // Mensagem enviada pelo usuário
            echo '<div class="d-flex justify-content-end mb-4">';
            echo '    <div class="msg_cotainer_send">';
            echo '        ' . $mensagem;
            echo '        <span class="msg_time_send">'. $dataHoraUTC .'</span>';
            echo '    </div>';
            echo '    <div class="img_cont_msg">';
            echo '        <img src="' . htmlspecialchars($imagem_usuario) . '" class="rounded-circle user_img_msg">';
            echo '    </div>';
            echo '</div>';
        } else {
            // Mensagem recebida
            echo '<div class="d-flex justify-content-start mb-4">';
            echo '    <div class="img_cont_msg">';
            echo '        <img src="' . htmlspecialchars($imagemSrc) . '" class="rounded-circle user_img_msg">';
            echo '    </div>';
            echo '    <div class="msg_cotainer">';
            echo '        ' . $mensagem;
            echo '        <span class="msg_time">'. $dataHoraUTC .'</span>';
            echo '    </div>';
            echo '</div>';
        }
    }
} else {
    echo '<p>No messages found.</p>';
}
?>
