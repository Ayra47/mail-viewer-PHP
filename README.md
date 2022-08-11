# mail-viewer-PHP

<h1>Как использовать</h1>
создаем страничку index.php, в нее кидаем код: </br>
<code>require_once('./MailViewer.php');</code>

- пароль должен быть паролем для приложений, а не основным от аккаунта
- включите smpt в почте
добавьте код:<br/>
<code>
    $mailViewer = new MailViewer('mail@yandex.ru', 'password');
</code>
<br/>
<code>
    $mails = $mailViewer->find();
</code>
<br/>
<code>
    echo $mails;
</code>
<p>По умолчанию выведет все письма с почты</p>
<br><br><br>
<h5>
    По умолчанию стоит почта яндекса, если хотите изменить<br/>
    на другую, то поменяйте 7 строчку<br/>
    "{imap.yandex.ru:993/imap/ssl}" -> "imap вашей почты"
</h5>
<br><br><br>
<h1>Функционал</h1>
<p>
    Опции для сортировки писем:
</p>
text<br>
date

<p>Пример:<p>
<code>
    $mails = $mailViewer->find([
        'text' => 'test',
        'date' => '2022-08-11'
    ]);
</code>
