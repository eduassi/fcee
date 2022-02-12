<?php
include_once '../config/settings.php';

function send_email($name, $email)
{
    $subject = "[Não Responda] Sua inscrição foi enviada [Não Responda]";
    $message = "
        <b>Olá $name</b>,
        <br>
        <br>Seu formulário de inscrição foi enviado! Agora você deve esperar pela homologação da sua inscrição.
        <br>Para dúvidas, entre em contato pelo e-mail  <a href='mailto:fcee.edbasica@gmail.com'>fcee.edbasica@gmail.com</a>. 
        <br>
        <br>
        <br>Atenciosamente,
        <br>Equipe FCEE
    ";

    $recipient = "no-reply@fcee-sc.net.br";

    $email_headers = implode("\n", array("From: $recipient", "Reply-To: $recipient", "Return-Path: $recipient", "MIME-Version: 1.0", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8"));
    $mail_flow = mail($email, $subject, $message, $email_headers);
    if ($mail_flow) {
        return true;
    }
    return false;
}


function send_approval_email($name, $email)
{
    $subject = "[Não Responda] Sua inscrição foi homologada! [Não Responda]";
    $message = "
        <b>Olá, $name,</b>
        <br>
        <br>Sua inscrição no curso <b>Educação Especial no contexto da educação básica: Aspectos teóricos e Metodológicos foi homologada</b>.
        <br>
        <br>Informamos que o curso iniciará no dia 21/03/2022 e ocorrerá no período de 02 (dois) meses.
        <br>
        <br>Na data de abertura, acesse o link: <a target='_blank' href='https://cursos.fcee-sc.net.br/'>https://cursos.fcee-sc.net.br/</a>
        <br>
        <br>Desejamos-lhe um excelente curso!
        <br>
        <br>Equipe FCEE 
    ";

    $recipient = "no-reply@fcee-sc.net.br";

    $email_headers = implode("\n", array("From: $recipient", "Reply-To: $recipient", "Return-Path: $recipient", "MIME-Version: 1.0", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8"));
    $mail_flow = mail($email, $subject, $message, $email_headers);
    if ($mail_flow) {
        return true;
    }
    return false;
}
