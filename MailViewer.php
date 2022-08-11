
<?php 
    class MailViewer{
        public $inbox;
        public function __construct($user, $password){
            $path = "INBOX";
            $this->inbox = imap_open("{imap.yandex.ru:993/imap/ssl}" . $path , $user, $password ) or die('Cannot connect to Yandex: ' . imap_last_error());
        }
        
        public function find($options){
            while (current($options)) {
                if (key($options) == 'text') {
                    $text = current($options);
                }
                if (key($options) == 'date') {
                    $orgDate  = current($options);
                    $date = str_replace('-"', '/', $orgDate);  
                    $newDate = date("j F Y", strtotime($orgDate));  
                }
            next($options);
            }
            $output = '';
            $newOptions = '';

            if (isset($text)) {
                $newOptions =  $newOptions . "TEXT $text ";
            }
            if (isset ($date)) {
                $newOptions = $newOptions . "ON \"$newDate\"";
            }

            $emails = imap_search($this->inbox, $newOptions ) or die('Таких писем нет: ' . imap_last_error());
            foreach($emails as $mail) {
                $headerInfo = imap_headerinfo($this->inbox, $mail);
        
                $output .= $headerInfo->subject.'<br/>';
                $output .= $headerInfo->toaddress.'<br/>';
                $output .= $headerInfo->date.'<br/>';
                $output .= $headerInfo->fromaddress.'<br/>';
                $output .= $headerInfo->reply_toaddress.'<br/>';
        
                $emailStructure = imap_fetchstructure($this->inbox,$mail);
        
                if(!isset($emailStructure->parts)) {
                    $output .= imap_body($this->inbox, $mail, FT_PEEK); 
                }
            }
            return $output;
        }
    };
?>