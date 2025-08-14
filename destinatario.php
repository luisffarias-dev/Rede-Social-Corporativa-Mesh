<?php
include 'conexao.php'; // Conectar ao banco de dados

if (isset($_POST['contato_selecionado'])) {
    $contatoSelecionado = $_POST['contato_selecionado'];

    // Consulta ao banco de dados para obter o e-mail do contato
    $sql = "SELECT Email FROM tb_contas WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $contatoSelecionado);
    $stmt->execute();
    $stmt->bind_result($emailContato);
    $stmt->fetch();
    $stmt->close();
    
    // Retornar o e-mail do contato
    if ($emailContato) {
        echo $emailContato;
    } else {
        echo "Contato não encontrado.";
    }

    $conn->close();
}
?>
