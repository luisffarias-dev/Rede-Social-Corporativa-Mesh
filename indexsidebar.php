<html lang="pt-br">
<head>
    <title>mesh</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div id="sidebar">
        <nav id="sidebar">
            <div id="sidebar_content">
                <!-- Aqui você faz o include do arquivo PHP que busca os dados -->
                <?php include 'sidebar_dados.php'; ?>
                <div id="user">
       
            <img src="data:image/jpeg;base64,<?php echo base64_encode($imagem_usuario); ?>" id="user_avatar" alt="Avatar">
                <p id="user_infos">
                <span class="item-description">
                 <?php echo $nome_usuario; ?> 
                 </span>
                       
                      </p>
                    
                </div>
             

                <ul id="side_items">
                    <li class="side-item active">
                        <a href="chamar_publis.html" target="_top">
                            <i class="fa-solid fa-house"></i>
                            <span class="item-description">
                                Pagina Inicial
                            </span>
                        </a>
                    </li>

                    <li class="side-item">
                        <a href="chamar_perfil.html" target="_top">
                            <i class="fa-solid fa-user"></i>
                            <span class="item-description">
                                Perfil
                            </span>
                        </a>
                    </li>

                    <li class="side-item">
                        <a href="chamar_busca.html" target="_top">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span class="item-description">
                                Busca
                            </span>
                        </a>
                    </li>

                    <li class="side-item">
                        <a href='chamar_chat.html' target="_top">
                            <i class="fa-solid fa-comments"></i>
                            <span class="item-description">
                                Chat
                            </span>
                        </a>
                    </li>

                    <li class="side-item">
                        <a href="chamar_jogos.html" target="_top">
                            <i class="fa-solid fa-gamepad"></i>
                            <span class="item-description">
                                Jogos
                            </span>
                        </a>
                    </li>

                    <br><br><br>
                    <li class="side-item">
                        <a href="index.html" target="_top">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="item-description">
                                Logout
                            </span>
                        </a>
                    </li>
                </ul>

                <button id="open_btn">
                    <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </nav>
    </div>

    <main>
        <div id="sugestao">
            <div class="sug">
            </div>
        </div>
    </main>

    <script src="script_sidebar.js"></script>
</body>
</html>
