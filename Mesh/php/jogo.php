<?php
session_start(); 
require 'conexao.php'; 

// Verifica se o usuário está logado
if (isset($_SESSION['email_usuario'])) {
    $emailUsuarioAtual = $_SESSION['email_usuario']; 
    $query = "SELECT Nome FROM tb_contas WHERE Email = '$emailUsuarioAtual'";
    $result = mysql_query($query);
    $usuarioAtual = mysql_result($result, 0);
} else {
    $usuarioAtual = 'Nome do Usuário'; 
}

$vitorias = $derrotas = $empates = 0;
$resultado = '';
$opcaoUsuario = '';
$jogadas = ['Pedra', 'Papel', 'Tesoura', 'Lagarto', 'Spock'];
$jogoFoiJogado = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['opcao']) && $_POST['opcao'] !== '') {
    $op = (int)$_POST['opcao'];
    $comp = rand(1, 5);
    $opcaoUsuario = $jogadas[$op - 1];
    $opcaoComputador = $jogadas[$comp - 1];
    $jogoFoiJogado = true; 

    function resultado($op, $comp, $jogadas) {
        echo "<div class='resultado'>";
        echo "<p>Você escolheu: <strong>{$jogadas[$op - 1]}</strong></p>";
        echo "<p>O computador escolheu: <strong>{$jogadas[$comp - 1]}</strong></p>";
        if ($op == $comp) {
            echo "<p>Resultado: <strong>Empate!</strong></p>";
            return "empate";
        }

        $ganhaDe = [
            1 => [3, 4],
            2 => [1, 5],
            3 => [2, 4],
            4 => [2, 5],
            5 => [3, 1]
        ];

        if (in_array($comp, $ganhaDe[$op])) {
            echo "<p>Resultado: <strong>Você ganhou!</strong></p>";
            return "vitoria";
        } else {
            echo "<p>Resultado: <strong>Você perdeu!</strong></p>";
            return "derrota";
        }
    }

    $resultado = resultado($op, $comp, $jogadas);

    if ($resultado == 'vitoria') {
        $query = "UPDATE leaderboard SET vitorias = vitorias + 1 WHERE nome = '$usuarioAtual'";
        $updateResult = mysql_query($query);
        
        if (mysql_affected_rows() === 0) {
            $query = "INSERT INTO leaderboard (nome, vitorias, Email) VALUES ('$usuarioAtual', 1, '$emailUsuarioAtual')";
            mysql_query($query);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedra, Papel, Tesoura, Lagarto, Spock</title>
    <link rel="stylesheet" href="jogo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header-links">
        <a href="leaderboard.php" class="link">
    <i class="fas fa-trophy"></i> Classificação
</a>
<a href="regras.php" class="link">
    <i class="fas fa-book"></i> Regras
</a>

        </div>
        <h1 id='jokenpo'>Bem-vindo ao JO-KEN-PO</h1>
        
        <form method="post" action="">
            <div class="icons">
                <div class="icon-container">
                    <label class="icon" data-opcao="1">
                        <i class="fas fa-hand-rock"></i> 
                    </label>
                    <span>Pedra</span>
                </div>
                <div class="icon-container">
                    <label class="icon" data-opcao="2">
                        <i class="fas fa-hand-paper"></i> 
                    </label>
                    <span>Papel</span>
                </div>
                <div class="icon-container">
                    <label class="icon" data-opcao="3">
                        <i class="fas fa-hand-scissors"></i>
                    </label>
                    <span>Tesoura</span>
                </div>
                <div class="icon-container">
                    <label class="icon" data-opcao="4">
                        <i class="fas fa-hand-lizard"></i> 
                    </label>
                    <span>Lagarto</span>
                </div>
                <div class="icon-container">
                    <label class="icon" data-opcao="5">
                        <i class="fas fa-hand-spock"></i> 
                    </label>
                    <span>Spock</span>
                </div>
            </div>
            <input type="hidden" name="opcao" id="opcao" value="">
            <p id="escolha_texto">Você escolheu: </p>  
            <button type="submit" class="btn" id="jogar-btn"><h3><?php echo $jogoFoiJogado ? 'Tentar novamente' : 'Jogar'; ?></h3></button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const opcao = this.getAttribute('data-opcao');
                document.getElementById('opcao').value = opcao;
                
                const jogadas = ['Pedra', 'Papel', 'Tesoura', 'Lagarto', 'Spock'];
                document.getElementById('escolha_texto').textContent = `Você escolheu: ${jogadas[opcao - 1]}`;
            });
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            const opcao = document.getElementById('opcao').value;
            if (!<?php echo json_encode($jogoFoiJogado); ?> && opcao === '') {
                event.preventDefault();
                alert('Escolha uma opção para jogar!');
            }
        });
    </script>
</body>
</html>
