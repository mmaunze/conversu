<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {
        
        // Dados do formulário
        $to = 'info@conversu.co.mz'; // E-mail de destino
        $subject = $_POST['subject']; // Assunto do e-mail
        
        // Sanitiza os dados do formulário
        $name = htmlspecialchars($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars($_POST['message']);
        
        // Verifica se o e-mail fornecido é válido
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            // Monta o corpo da mensagem
            $message_body = "Nome: $name\n";
            $message_body .= "Email: $email\n";
            $message_body .= "Mensagem:\n$message\n";
            
            // Cabeçalhos do e-mail
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Envia o e-mail
            if (mail($to, $subject, $message_body, $headers)) {
                echo "Sua mensagem foi enviada com sucesso. Obrigado!";
            } else {
                echo "Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.";
            }
            
        } else {
            echo "Erro: Por favor, forneça um endereço de e-mail válido.";
        }
        
    } else {
        echo "Erro: Todos os campos do formulário são obrigatórios.";
    }
} else {
    echo "Erro: O formulário de contato não foi submetido corretamente.";
}
?>
