<?php
session_start();

// Função para gerar uma carta aleatória
function gerarCarta() {
    return rand(1, 11); // Cartas de 1 a 11 (simplificado para evitar cartas com valores específicos como Rei, Dama, Valete)
}

// Função para calcular a soma total das cartas
function calcularTotal($cartas) {
    return array_sum($cartas);
}

// Função para iniciar o jogo ou reiniciar
function iniciarJogo() {
    $_SESSION['jogador'] = [gerarCarta(), gerarCarta()]; // Jogador recebe duas cartas
    $_SESSION['dealer'] = [gerarCarta()]; // Dealer começa com uma carta visível
    $_SESSION['fim_jogo'] = false;
}

// Inicia o jogo se ainda não houver sessão
if (!isset($_SESSION['jogador'])) {
    iniciarJogo();
}

// Jogador puxa uma carta
if (isset($_POST['puxar'])) {
    $_SESSION['jogador'][] = gerarCarta();
    if (calcularTotal($_SESSION['jogador']) > 21) {
        $_SESSION['fim_jogo'] = "Você perdeu! A soma das suas cartas ultrapassou 21.";
    }
}

// Jogador para, e o dealer começa a puxar cartas até ter pelo menos 17 pontos
if (isset($_POST['parar'])) {
    while (calcularTotal($_SESSION['dealer']) < 17) {
        $_SESSION['dealer'][] = gerarCarta();
    }
    $jogadorTotal = calcularTotal($_SESSION['jogador']);
    $dealerTotal = calcularTotal($_SESSION['dealer']);
    if ($dealerTotal > 21 || $jogadorTotal > $dealerTotal) {
        $_SESSION['fim_jogo'] = "Parabéns, você ganhou!";
    } elseif ($jogadorTotal == $dealerTotal) {
        $_SESSION['fim_jogo'] = "Empate!";
    } else {
        $_SESSION['fim_jogo'] = "O dealer ganhou!";
    }
}

// Reiniciar o jogo
if (isset($_POST['reiniciar'])) {
    iniciarJogo();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Blackjack</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #2c3e50; color: #ecf0f1; }
        .game-container { max-width: 400px; margin: auto; padding: 20px; border-radius: 10px; background-color: #34495e; }
        .cards { margin: 10px 0; }
        .button { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .pull { background-color: #27ae60; color: #fff; }
        .stop { background-color: #c0392b; color: #fff; }
        .reset { background-color: #2980b9; color: #fff; }
    </style>
</head>
<body>

<div class="game-container">
    <h1>Blackjack</h1>
    <div>
        <h3>Suas cartas:</h3>
        <div class="cards">
            <?php foreach ($_SESSION['jogador'] as $carta) echo $carta . " "; ?>
        </div>
        <h3>Total: <?php echo calcularTotal($_SESSION['jogador']); ?></h3>
    </div>

    <div>
        <h3>Cartas do dealer:</h3>
        <div class="cards">
            <?php foreach ($_SESSION['dealer'] as $carta) echo $carta . " "; ?>
        </div>
        <h3>Total: <?php echo calcularTotal($_SESSION['dealer']); ?></h3>
    </div>

    <?php if ($_SESSION['fim_jogo']): ?>
        <h2><?php echo $_SESSION['fim_jogo']; ?></h2>
        <form method="post">
            <button class="button reset" type="submit" name="reiniciar">Reiniciar Jogo</button>
        </form>
    <?php else: ?>
        <form method="post">
            <button class="button pull" type="submit" name="puxar">Puxar Carta</button>
            <button class="button stop" type="submit" name="parar">Parar</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
