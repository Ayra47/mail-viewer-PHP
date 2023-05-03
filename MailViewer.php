
<?php
class MailViewer
{
    protected $inbox;
    protected $code;
    protected $emails;

    public function __construct(string $user, string $password, bool $code = false)
    {
        $path = "INBOX";
        $this->code = $code;

        try {
            $this->inbox = imap_open("{imap.yandex.ru:993/imap/ssl}" . $path, $user, $password);
        } catch (Exception $e) {
            'Cannot connect to Yandex: ' . $e;
        }
    }

    /**
     * @param $options параметры, для поиска в методе params
     */
    public function getMessages(array $options = [])
    {
        $newOptions = $this->params($options);
        $this->emails = $this->searchMessages($newOptions);

        return $this;
    }
    
    /**
     * @param $options Принимает ассоц. массив для поиска по тексту и дате
     */
    public function params(array $options) : string
    {
        $text = $date = $newOptions = "";

        if (array_key_exists('text', $options)) {
            $text = $options['text'];
            $newOptions .= "TEXT $text";
        }

        if (array_key_exists('date', $options)) {
            $orgDate = $options['date'];
            $date = date("j F Y", strtotime(str_replace("-", "/", $orgDate)));
            $newOptions .= "ON \"$date\"";
        }

        if (array_key_exists('unread', $options) && $options['unread']) {
            $newOptions .= "UNSEEN";
        }

        return $newOptions;
    }

    public function searchMessages(string $newOptions)
    {
        // Иногда возникает ошибка при подключении к почте, поэтому пробуем пару раз с небольшим интервалом
        $iterations = 0;
        do {
            $emails = imap_search($this->inbox, $newOptions) or $imapError = imap_last_error();
            if ($emails === false) {
                sleep(1);
            } else {
                break;
            }
            $iterations++;
        } while ($iterations <= 5);

        if (isset($imapError)) {
            return $imapError;
        }

        return $emails;
    }

    public function output()
    {
        foreach ($this->emails as $mail) {
            $headerInfo = imap_headerinfo($this->inbox, $mail);

            if ($this->code) {
                $output[] = [
                    'date' => $headerInfo->date,
                    'fromaddress ' => $headerInfo->from[0]->mailbox . "@" . $headerInfo->from[0]->host,
                    'title' => $headerInfo->subject,
                    'text' => imap_body($this->inbox, $mail, FT_PEEK),
                ];
            } else {
                $output = "";
    
                $output .= $headerInfo->subject . '<br/>';
                $output .= $headerInfo->toaddress . '<br/>';
                $output .= $headerInfo->date . '<br/>';
                $output .= $headerInfo->fromaddress . '<br/>';
                $output .= $headerInfo->reply_toaddress . '<br/>';
    
                $emailStructure = imap_fetchstructure($this->inbox, $mail);
    
                if (!isset($emailStructure->parts)) {
                    $output .= imap_body($this->inbox, $mail, FT_PEEK);
                }
            }
        }

        return $output;
    }
};
?>