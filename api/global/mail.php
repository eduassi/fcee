<?php
include_once '../config/settings.php';

function send_email($nickname, $code, $email, $username, $password)
{
    $subject = "[NO-REPLY] Código de Registro RICON [NO-REPLY]";
    $message = "
        Olá  $nickname,
        <br>
        <br>Você está a 1 passo de se cadastrar no RICON, a melhor ferramenta para criação completa de cursos!
        <br>
        <br>Para finalizar seu cadastro acesse:
        <br>https://ricon.generalwebsolutions.com.br/#finishRegister 
        <br>
        <br>Insira seus dados e o código de acesso.
        <br>Email: $email
        <br>Nome da conta: $username
        <br>Código de acesso: $code
        <br>
        <br>Um lembrete, sua senha é: $password
    ";

    $recipient = "contato@ricon.generalwebsolutions.com.br";

    $email_headers = implode("\n", array("From: $recipient", "Reply-To: $recipient", "Return-Path: $recipient", "MIME-Version: 1.0", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8"));
    $mail_flow = mail($email, $subject, $message, $email_headers);
    if ($mail_flow) {
        return true;
    }
    echo var_dump($mail_flow);
    return false;
}
