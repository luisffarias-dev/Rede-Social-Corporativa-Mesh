$(document).ready(function() {
    $('#chatForm').on('submit', function(e) {
        e.preventDefault(); // Evita o comportamento padrão de envio do formulário

        var message = $('#message').val();
        var emailSelecionado = $('#emailSelecionado').val();
        var emailsessao = $('#emailsessao').val();

        $.ajax({
            type: 'POST',
            url: 'enviar.php', // Arquivo PHP que processa o envio
            data: {
                message: message,
                emailSelecionado: emailSelecionado,
                emailsessao: emailsessao
            },
            success: function(response) {
                // Após enviar, carrega as mensagens novamente
                $('#chatMessages').html(response); // Atualiza a área das mensagens
                $('#message').val(''); // Limpa a caixa de texto
            },
            error: function() {
                alert('Erro ao enviar a mensagem.');
            }
        });
    });

    // Função para recarregar automaticamente as mensagens a cada X segundos
    setInterval(function() {
        $.ajax({
            url: 'carregar_mensagens.php',
            type: 'POST',
            data: {
                emailSelecionado: $('#emailSelecionado').val(),
                emailsessao: $('#emailsessao').val()
            },
            success: function(response) {
                $('#chatMessages').html(response); // Atualiza a área das mensagens
            }
        });
    }, 1000); // A cada 5 segundos
});
