<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['email_usuario'])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: index.html");
    exit();
}

// Conecta ao banco de dados
include 'conexao.php';

// Obtém o e-mail do usuário logado da sessão
$email = $_SESSION['email_usuario'];

// Inicializa os campos de atualização
$campos = [];

// Verifica cada campo antes de adicioná-lo à consulta SQL
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
    $campos[] = "imagem = '" . mysql_real_escape_string($imagem) . "'";
}

if (!empty($_POST['instagram'])) {
    $campos[] = "Instagram = '" . mysql_real_escape_string($_POST['instagram']) . "'";
}
if (!empty($_POST['linkedin'])) {
    $campos[] = "Linkedin = '" . mysql_real_escape_string($_POST['linkedin']) . "'";
}
if (!empty($_POST['facebook'])) {
    $campos[] = "Facebook = '" . mysql_real_escape_string($_POST['facebook']) . "'";
}
if (!empty($_POST['cargo'])) {
    $campos[] = "Cargo = '" . mysql_real_escape_string($_POST['cargo']) . "'";
}
if (!empty($_POST['empresa'])) {
    $campos[] = "Empresa = '" . mysql_real_escape_string($_POST['empresa']) . "'";
}
if (!empty($_POST['nasc'])) {
    $campos[] = "Data_Nascimento = '" . mysql_real_escape_string($_POST['nasc']) . "'";
}
if (!empty($_POST['telefone'])) {
    $campos[] = "Telefone = '" . mysql_real_escape_string($_POST['telefone']) . "'";
}
if (!empty($_POST['habilidade'])) {
    $campos[] = "Habilidades = '" . mysql_real_escape_string($_POST['habilidade']) . "'";
}
if (!empty($_POST['interesses'])) {
    $campos[] = "Interesses = '" . mysql_real_escape_string($_POST['interesses']) . "'";
}
if (!empty($_POST['estado'])) {
    $campos[] = "Estado = '" . mysql_real_escape_string($_POST['estado']) . "'";
}
if (!empty($_POST['cidade'])) {
    $campos[] = "Cidade = '" . mysql_real_escape_string($_POST['cidade']) . "'";
}

// Monta a consulta apenas se houver campos para atualizar
if (!empty($campos)) {
    $sql = "UPDATE tb_contas SET " . implode(', ', $campos) . " WHERE Email = '" . mysql_real_escape_string($email) . "'";

    // Executa a consulta
    if (mysql_query($sql)) {
        echo "Informações atualizadas com sucesso.";
        header("Location: mainperfil.php");
        exit();
    } else {
        echo "Erro ao atualizar informações: " . mysql_error();
    }
} else {
    echo "Nenhuma alteração foi feita, pois nenhum campo foi preenchido.";
}

// Fecha a conexão com o banco de dados
mysql_close();
?>
