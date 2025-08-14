<?php
session_start(); 
require 'conexao.php'; 

// Verifica se o usuário está logado
if (isset($_SESSION['email_usuario'])) {
    $emailUsuarioAtual = $_SESSION['email_usuario']; 
    $queryUsuario = "SELECT Nome, Imagem FROM tb_contas WHERE Email = '$emailUsuarioAtual'";
    $resultUsuario = mysql_query($queryUsuario);
    
    if ($resultUsuario && mysql_num_rows($resultUsuario) > 0) {
        $usuarioAtual = mysql_fetch_assoc($resultUsuario);
        $nomeUsuario = $usuarioAtual['Nome'];
        $imagemUsuario = 'data:image/jpeg;base64,' . base64_encode($usuarioAtual['Imagem']);
        
        // Consulta para obter vitórias do usuário logado
        $queryVitorias = "SELECT vitorias_jogo1 FROM leaderboard WHERE nome = '$nomeUsuario'";
        $resultVitorias = mysql_query($queryVitorias);
        $vitoriasUsuario = ($resultVitorias && mysql_num_rows($resultVitorias) > 0) ? mysql_fetch_assoc($resultVitorias)['vitorias_jogo1'] : 0;
    } else {
        $nomeUsuario = 'Nome do Usuário'; 
        $imagemUsuario = ''; 
        $vitoriasUsuario = 0;
    }
} else {
    $nomeUsuario = 'Nome do Usuário'; 
    $imagemUsuario = ''; 
    $vitoriasUsuario = 0;
}

// Consultando o leaderboard com INNER JOIN
$query = "SELECT tb_contas.Nome, leaderboard.vitorias_jogo1, tb_contas.Imagem 
          FROM tb_contas 
          INNER JOIN leaderboard ON tb_contas.Nome = leaderboard.nome 
          ORDER BY leaderboard.vitorias_jogo1 DESC";
$result = mysql_query($query);

// Armazenando os resultados do leaderboard em um array
$ranking = [];
while ($row = mysql_fetch_assoc($result)) {
    $row['Imagem'] = 'data:image/jpeg;base64,' . base64_encode($row['Imagem']);
    $ranking[] = $row;
    
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="jogo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Classificação</h1>
        
        <?php if ($imagemUsuario): ?>
            <div class="user-score">
                <img src="<?= htmlspecialchars($imagemUsuario) ?>" alt="<?= htmlspecialchars($nomeUsuario) ?>" class="user-image">
                <p><?= htmlspecialchars($nomeUsuario) ?> - <?= htmlspecialchars($vitoriasUsuario) ?> vitórias</p>
            </div>
        <?php endif; ?>

        <ul class="leaderboard">
            <?php foreach ($ranking as $posicao => $usuario): ?>
                <li>
                    <img src="<?= htmlspecialchars($usuario['Imagem']) ?>" alt="<?= htmlspecialchars($usuario['Nome']) ?>" class="user-image">
                    <?php
                    // Adicionando ícones para os 1º, 2º e 3º lugares
                    if ($posicao == 0) {
                        echo '<i class="fas fa-trophy" style="color: gold;"></i>';
                    } elseif ($posicao == 1) {
                        echo '<i class="fas fa-medal" style="color: silver;"></i>';
                    } elseif ($posicao == 2) {
                        echo '<i class="fas fa-medal" style="color: #cd7f32;"></i>';
                    } else {
                        echo ($posicao + 1) . ".";
                    }
                    ?>
                    <?= htmlspecialchars($usuario['Nome']) . " - " . $usuario['vitorias_jogo1'] ?> vitórias
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="jogo.php" class="link">
    <i class="fas fa-arrow-left"></i> <h4>Voltar ao Jogo</h4>
</a>

    </div>
</body>
</html>
