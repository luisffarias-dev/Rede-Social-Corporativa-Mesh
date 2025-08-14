<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="batepapo.css">
</head>
<body>
<?php
session_start();
$email_usuario = $_SESSION['email_usuario'];
if (isset($_GET['email'])) {
    $email_Selecionado = $_GET['email'];
} else {
$email_Selecionado = ''; // Defina o valor apropriado aqui
}
include 'conexao.php';

// Consulta para pegar nome e imagem do usuário
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
?>

<div class="container-fluid h-100">
    <div class="row justify-content-center h-100">
        <!-- Coluna de Contatos -->
        <div class="col-md-4 col-xl-3 chat">
            <div class="card mb-sm-3 mb-md-0 contacts_card">
                <div class="card-header">
                    <!-- Formulário de busca -->
                    <form method="post" action="batepapo.php">
                        <div class="input-group">
                            <input type="text" placeholder="Search..." name="search" class="form-control search">
                            <div class="input-group-prepend">
                                <button class="input-group-text search_btn" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body contacts_body">
                    <ul class="contacts" id="contacts-list">
                    <?php
                    // Conexão com o banco de dados
                    include 'conexao.php';

                    if (isset($_POST['search']) ) {
                        $searchTerm = $_POST['search'];
                        $query = "SELECT Nome, Email, Imagem FROM tb_contas WHERE Nome LIKE '%$searchTerm%' LIMIT 15";
                        $result = mysql_query($query);

                        if (mysql_num_rows($result) > 0) {
                            while ($row = mysql_fetch_assoc($result)) {
                                if (!empty($row['Imagem'])) {
                                    $imagemData = base64_encode($row['Imagem']);
                                    $imagemSrc = 'data:image/jpeg;base64,' . $imagemData;
                                } else {
                                    $imagemSrc = './Images/noPhoto.png'; // Imagem padrão
                                }
                                
                                // Cada li contém o nome e o email do contato
                                echo '<li class="contact-item">
                                <a href="batepapo.php?contato=' . urlencode($row['Nome']) . '&email=' . urlencode($row['Email']) . '" class="d-flex bd-highlight" style="text-decoration: none; color: inherit;">
                                  <div class="img_cont">
                                    <img src="' . $imagemSrc . '" class="rounded-circle user_img" style="width: 50px; height: 50px;">
                                  </div>
                                  <div class="user_info">
                                    <span>' . htmlspecialchars($row['Nome']) . '</span>
                                  </div>
                                </a>
                                <hr>
                            </li>';
                     
                            }
                        }
                    }
                    ?>
                    </ul>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>

        <!-- Coluna de Mensagens do Chat -->
        <div class="col-md-8 col-xl-6 chat">
            <div class="card">
                <div class="card-header msg_head">
                    <div class="d-flex bd-highlight">
                        <div class="img_cont">
                        <?php
// Verifica se um e-mail foi passado pela URL
if (isset($_GET['email'])) {
    // Se 'email' estiver presente, utiliza o valor de 'email' para a query
    $contatoSelecionado = $_GET['email'];
    $query = "SELECT Nome, Email, Imagem FROM tb_contas WHERE Email = '" . mysql_real_escape_string($contatoSelecionado) . "'";
} elseif (isset($_GET['contato'])) {
    // Se 'email' não foi passado, então utiliza 'contato'
    $contatoSelecionado = $_GET['contato'];
    $query = "SELECT Nome, Email, Imagem FROM tb_contas WHERE Nome = '" . mysql_real_escape_string($contatoSelecionado) . "'";
} else {
    // Caso nenhum dos parâmetros 'email' ou 'contato' seja passado, exibe uma imagem padrão
    echo '<img src="./Images/noPhoto.png" class="rounded-circle user_img" style="width: 70px; height: 70px;">'; // Imagem padrão caso não tenha contato selecionado
    exit; // Encerra o script
}

// Executa a query
$result = mysql_query($query);

// Verifica se a consulta retornou algum resultado
if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    $email_Selecionado = $row['Email'];

    // Converte a imagem do contato para base64, se disponível
    if (!empty($row['Imagem'])) {
        $imagemSrc = 'data:image/jpeg;base64,' . base64_encode($row['Imagem']);
    } else {
        $imagemSrc = './Images/noPhoto.png'; // Imagem padrão
    }

    // Exibe a imagem e o nome do contato
    echo '<a href="mainperfil.php?email=' . urlencode($email_Selecionado) . '"><img src="' . $imagemSrc . '" class="rounded-circle user_img" style="width: 70px; height: 70px;"></a>';
    echo '<input type="hidden" name="emailSelecionado" id="email-selecionado" value="' . htmlspecialchars($row['Email']) . '">';
} else {
    // Caso a consulta não retorne resultados, exibe uma imagem padrão
    echo '<img src="./Images/noPhoto.png" class="rounded-circle user_img" style="width: 70px; height: 70px;">'; // Imagem padrão
}
?>

                        </div>
                        <div class="user_info">
                            <?php
                            if (isset($row['Nome'])) {
                                echo '<span>' . htmlspecialchars($row['Nome']) . '</span>';
                            } else {
                                echo '<span>Selecione um contato</span>';
                            }
                            $sqlcount = "SELECT COUNT(*) AS total_mensagens FROM tb_chat 
                            WHERE (Autor = '$email_usuario' AND Destinatario = '$email_Selecionado') 
                            OR (Autor = '$email_Selecionado' AND Destinatario = '$email_usuario')";
                            $contagem = mysql_query($sqlcount);

                            if ($contagem) {
                                $resultado = mysql_fetch_assoc($contagem);
                                echo '<p>Você tem ' . $resultado['total_mensagens'] . ' mensagens entre você e contato selecionado </p>';
                                } else {
                                 echo '<p>Erro na contagem de mensagens.</p>';
                            } ?></div>
                        <div class="video_cam">
                           
                        </div>
                    </div>
                    <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                    <div class="action_menu">
                        <ul>
                          
                        </ul>
                    </div>
                </div>
                
                <div class="card-body msg_card_body">
                    <div id="chat-messages">
                        <?php
                        echo '<input type="hidden" name="emailSelecionado" value="' . htmlspecialchars($email_Selecionado) . '">';
                        echo '<input type="hidden" name="emailsessao" value="' . htmlspecialchars($email_usuario) . '">';
                        ?>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text attach_btn"><i class="fas fa-message"></i></span>
                        </div>

                        <form id="chatForm" class="flex-grow-1">
                            <input type="hidden" id="emailSelecionado" name="emailSelecionado" value="<?php echo htmlspecialchars($email_Selecionado); ?>">
                            <input type="hidden" id="emailsessao" name="emailsessao" value="<?php echo htmlspecialchars($email_usuario); ?>">
                            <input type="text" id="message" name="message" class="form-control" placeholder="Digite sua mensagem...">
                        </form>

                        <div class="input-group-append">
                            <button type="submit" form="chatForm" class="input-group-text send_btn"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
    $('.contact-item').on('click', function() {
        const nome = $(this).data('nome');
        const email = $(this).data('email');
        window.location.href = 'batepapo.php?contato=' + encodeURIComponent(nome) + '&email=' + encodeURIComponent(email);
    });
});
</script>

<script type="text/javascript">
// Função para buscar as mensagens do servidor
function carregarMensagens() {
    const emailSelecionado = document.getElementById('emailSelecionado').value;
    const emailsessao = document.getElementById('emailsessao').value;

    fetch('carregar_mensagens.php?emailSelecionado=' + encodeURIComponent(emailSelecionado) + '&emailsessao=' + encodeURIComponent(emailsessao))
    .then(response => response.text())
    .then(result => {
        // Atualiza o conteúdo da área de mensagens com as novas mensagens
        document.getElementById('chat-messages').innerHTML = result;
    })
    .catch(error => {
        console.error('Erro ao carregar as mensagens:', error);
    });
}

// Chama a função para carregar mensagens ao abrir a página
window.onload = carregarMensagens;

// Atualiza as mensagens a cada 2 segundos
setInterval(carregarMensagens, 2000); // 2000 milissegundos = 2 segundos

document.getElementById('chatForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio tradicional do formulário

    var formData = new FormData(this); // Cria os dados do formulário

    // Faz a requisição AJAX
    fetch('enviar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Recebe a resposta como texto
    .then(result => {
        // Atualiza o chat ou faz algo com o resultado
        carregarMensagens(); // Chama a função para atualizar as mensagens

        // Limpa o campo de mensagem após o envio
        document.getElementById('message').value = '';
    })
    .catch(error => {
        console.error('Erro ao enviar a mensagem:', error);
    });
});
</script>
</body>
</html>
