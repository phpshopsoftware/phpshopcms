<?php

/**
 * ���������� ����������� �����
 * @version 2.2
 * @package PHPShopClass
 * @tutorial http://doc.phpshop.ru/PHPShopClass/PHPShopMail.html
 * <code>
 * // example:
 * $PHPShopMail= new PHPShopMail('user@localhost','admin@localhost','Test','Hi, user!');
 * </code>
 * @param string $to ����
 * @param string $from �� ����
 * @param string $zag ��������� ������
 * @param string $content ���������� ������
 * @param boolean $type ��� ������, ����� ��� html, true ='text/html', false = text/plain
 * @param boolean $noSend true - �� �������� ������ ��� �������� ���������� ������. 
 * @param array $option �������������� ��������� SMTP 
 */
class PHPShopMail {

    /**
     * @var string ��������� ������
     */
    var $codepage = "windows-1251";

    /**
     * @var string ��� ����������
     */
    var $type = "text/plain";
    var $debug = false;

    /**
     * �����������
     */
    function __construct($to, $from, $subject, $content, $type = false, $noSend = false, $option = false) {
        global $PHPShopSystem, $_classPath;

        if (empty($_classPath))
            $_classPath = './phpshop/';

        // ���������
        require_once $_classPath . 'lib/phpmailer/PHPMailerAutoload.php';
        $this->mail = new PHPMailer;
        $this->PHPShopSystem = $PHPShopSystem;
        $option = $this->PHPShopSystem->getMailOption($option);

        $GLOBALS['SysValue']['other']['serverPath'] = $_SERVER['SERVER_NAME'] . "/" . $GLOBALS['SysValue']['dir']['dir'];

        // �������������� ���������
        if (is_array($option)) {

            // Replyto
            if (!empty($option['replyto']))
                $this->mail->addReplyTo($option['replyto']);

            // SMTP
            if ($option['smtp'] == true) {
                $this->mail->isSMTP();
                $this->mail->SMTPDebug = $option['debug'];
                $this->mail->Debugoutput = 'html';
                $this->mail->Host = $option['host'];
                $this->mail->Port = $option['port'];
                $this->SMTPSecure = $option['tls'];
                $this->mail->SMTPAuth = $option['auth'];
                $this->mail->Username = $option['user'];
                $this->mail->Password = $option['password'];


                if (!empty($option['auth']))
                    $this->mail->SMTPAuth = true;


                // Debug
                if ($this->debug)
                    $this->mail->SMTPDebug = 2;
            }

            // SENDMAIL
            elseif ($option['sendmail'] == true) {
                $this->mail->isMail();
            }
        } else {
            $this->mail->isMail();
        }

        $this->mail->XMailer = 'PHPShop';
        $this->mail->CharSet = $this->codepage;

        if (!empty($type)) {
            $this->type = 'text/html';
            $this->mail->isHTML(true);
            $this->mail->msgHTML($content);
        } else {
            $this->mail->isHTML(false);
            $this->mail->Body = $content;
        }


        if (strstr($from, ',')) {
            $from_array = explode(",", $from);
            $this->from = trim($from_array[0]);
        }
        else
            $this->from = $from;

        $this->mail->setFrom($this->from, $PHPShopSystem->getName());


        if (strstr($to, ',')) {
            $to_array = explode(",", $from);

            // ��������� �������
            if (is_array($to_array)) {
                foreach ($to_array as $k => $mail)
                    if ($k == 0)
                        $this->mail->addAddress($mail);
                    else
                        $this->mail->addBCC($mail);
            }
        }
        else
            $this->mail->addAddress($to);


        $this->mail->Subject = $subject;

        if (empty($noSend)) {
            $this->sendMail();
        } else {
            $this->setOrgData();
        }
    }

    /**
     * ������������� ����� ������ ��������
     */
    function setOrgData() {

        if ($this->PHPShopSystem) {
            $GLOBALS['SysValue']['other']['adminMail'] = $this->PHPShopSystem->getEmail();
            $GLOBALS['SysValue']['other']['telNum'] = $this->PHPShopSystem->getParam('tel');
            $GLOBALS['SysValue']['other']['org_name'] = $this->PHPShopSystem->getSerilizeParam('bank.org_name');
            $GLOBALS['SysValue']['other']['org_adres'] = $this->PHPShopSystem->getSerilizeParam('bank.org_adres');
            $GLOBALS['SysValue']['other']['logo'] = $this->PHPShopSystem->getParam('logo');

            $GLOBALS['SysValue']['other']['shopName'] = $this->PHPShopSystem->getName();
            $GLOBALS['SysValue']['other']['serverPath'] = $_SERVER['SERVER_NAME'] . "/" . $GLOBALS['SysValue']['dir']['dir'];
            $GLOBALS['SysValue']['other']['date'] = date("d-m-y H:i a");
        }
    }

    /**
     * ����������� ������ html
     * @param string $content ����������
     */
    function sendMailNow($content) {
        $this->mail->msgHTML($content);
        $result = $this->mail->send();
        if (!$result and $this->debug) {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        } elseif ($this->debug) {
            echo "Message sent!";
        }
        return $result;
    }

    /**
     * ����������� ������ text
     * @param string $content ����������
     * @param strong $header ���������
     */
    function sendMail() {
        $result = $this->mail->send();
        if (!$result and $this->debug) {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        } elseif ($this->debug) {
            echo "Message sent!";
        }
        return $result;
    }

}

class PHPShopMailFile extends PHPShopMail {

    function __construct($to, $from, $subject, $content, $filename, $file, $option = false) {
        parent::__construct($to, $from, $subject, $content, true, true, $option);
        $this->mail->addAttachment($file, $filename);
        $this->sendMailNow($content);
    }

}

?>