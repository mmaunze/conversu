<?php
class PHP_Email_Form {
    public $to = '';
    public $from_name = '';
    public $from_email = '';
    public $subject = '';
    public $messages = [];
    public $ajax = false;

    public function add_message($content, $label, $priority = 10) {
        $this->messages[] = [
            'content' => $content,
            'label' => $label,
            'priority' => $priority
        ];
    }

    public function send() {
        if (empty($this->to) || empty($this->from_email)) {
            return 'Erro: E-mail de destino ou e-mail do remetente não definidos!';
        }

        $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
        $headers .= "Reply-To: " . $this->from_email . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message_body = '';
        foreach ($this->messages as $message) {
            $message_body .= '<p><strong>' . htmlspecialchars($message['label']) . ':</strong> ' . htmlspecialchars($message['content']) . '</p>';
        }

        if (mail($this->to, $this->subject, $message_body, $headers)) {
            return 'Mensagem enviada com sucesso!';
        } else {
            return 'Erro: Não foi possível enviar a mensagem.';
        }
    }
}
?>
