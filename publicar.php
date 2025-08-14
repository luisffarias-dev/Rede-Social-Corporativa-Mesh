<?php
include 'conexao.php';

session_start();
if (!isset($_SESSION['email_usuario'])) {
    header('Location: index.php');
    exit();
}

date_default_timezone_set('America/Sao_Paulo');

$data = date('Y-m-d');
$hora = date('H:i:s');
$conteudo = $_POST['textarea'];
$email_usuario = $_SESSION['email_usuario'];
$email_Selecionado = ''; // Defina o valor apropriado aqui

// Verifica se a pasta de uploads existe, senão cria
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
}

// Verifica se foi enviado um arquivo
if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
    $caminhoImagem = $diretorio . basename($_FILES['file']['name']);
    
    // Move o arquivo para o diretório especificado
    if (move_uploaded_file($_FILES['file']['tmp_name'], $caminhoImagem)) {
        echo "Arquivo enviado com sucesso!";
    } else {
        echo "Erro ao enviar o arquivo.";
        $caminhoImagem = null; // Define como nulo se houver erro
    }
} else {
    $caminhoImagem = null; // Se nenhum arquivo foi enviado
}

$query = "SELECT Nome, Imagem FROM tb_contas WHERE Email = '$email_usuario'";
$result = mysql_query($query);
 
// Verifica se a consulta foi executada corretamente
if (!$result) {
    die('Erro na consulta: ' . mysql_error());
}
 
// Verifica se encontrou algum resultado
if ($row = mysql_fetch_assoc($result)) {
    // Atribui os dados às variáveis
    $nome_usuario = $row['Nome'];
    $imagem_usuario = $row['Imagem'];
   
    // Converte a imagem para base64 se não estiver vazia
    if (!empty($imagem_usuario)) {
        $imagem_usuario = 'data:image/jpeg;base64,' . base64_encode($imagem_usuario);
    } else {
        $imagem_usuario = './Images/noPhoto.png'; // Imagem padrão
    }
} else {
    $nome_usuario = "Usuário não encontrado";
    $imagem_usuario = './Images/noPhoto.png'; // Imagem padrão
}


// Insere os dados no banco de dados, incluindo o caminho da imagem
$sql = "INSERT INTO tb_posts (Autor, Email, Dia, Hora, Conteudo, Imagem, AutImg) VALUES ('$nome_usuario', '$email_usuario', '$data', '$hora', '$conteudo', '$caminhoImagem', '$imagem_usuario')";

if (mysql_query($sql)) {
    header("Location: escreverpubli.php");
    exit();
} else {
    echo "Erro ao publicar: " . mysql_error();
}
?>
