<?php
require_once('./MailViewer.php');

$mailViewer = new MailViewer('example@yandex.ru', 'example_pass', true);
$mails = $mailViewer->getMessages(['unread' => true])->output();

print_r($mails);