<?php
include 'conexao.php';



date_default_timezone_set('America/Sao_Paulo');

$autor = isset($_POST['emailsessao']) ? mysql_real_escape_string($_POST['emailsessao']) : '';
$conteudo = $_POST["message"];
$data = date('Y-m-d');
$hora = date('H:i:s');
$destinatario = $_POST['emailSelecionado'];

$sql = "INSERT INTO tb_chat (Mensagem, Dia, Hora, Autor, Destinatario) VALUES ('$conteudo', '$data', '$hora', '$autor', '$destinatario')";
mysql_query($sql);


?>
