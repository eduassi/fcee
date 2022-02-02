<?php
include_once '../config/settings.php';

function send_email($name, $email)
{
    $subject = "Sua inscrição foi enviada [Não Responda]";
    $message = "
        Olá  $name,
        <br>
        <br>Seu formulário de inscrição foi enviado! Agora você deve esperar pela homologação da sua inscrição.
        <br>
        <br>Atenciosamente,
        <br>Equipe FCEE
    ";

    $recipient = "contato@fcee-sc.net.br";

    $email_headers = implode("\n", array("From: $recipient", "Reply-To: $recipient", "Return-Path: $recipient", "MIME-Version: 1.0", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8"));
    $mail_flow = mail($email, $subject, $message, $email_headers);
    if ($mail_flow) {
        return true;
    }
    echo var_dump($mail_flow);
    return false;
}
