# mail-viewer-PHP

Как использовать:
создаем страничку index.php, в нее кидаем код: 
require_once('./MailViewer.php');

Подключение к почте:
- пароль должен быть паролем для приложений, а не основным от аккаунта
- включите smpt в почте

добавьте код:
$mailViewer = new MailViewer('mail@yandex.ru', 'password');
$mails = $mailViewer->find([
    'text' => 'текст_поиска',
    'date' => 'дата_поиска'
]);

echo $mails;