<?php /** @noinspection PhpArrayUsedOnlyForWriteInspection */
/** @noinspection AccessModifierPresentedInspection */
/** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection AutoloadingIssuesInspection */

/**
 * Pagination Component, responsible for managing the DATA required for pagination.
 */

/**
 * Class EmailComponent
 */
class EmailComponent extends Object
{

    var $smtpServer = "localhost";
    var $port       = "25";
    var $timeout    = "30";
    var $username   = "youruser";
    var $password   = "yourpassword";
    var $localhost  = "localhost";
    var $newLine    = "\r\n";

    /* * * * CONFIGURATION END * * * * */

    /**
     * @param $from
     * @param $namefrom
     * @param $to
     * @param $nameto
     * @param $subject
     * @param $message
     * @param $smtpServer
     * @param $port
     * @param $timeout
     * @return string
     */
    function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message, $smtpServer, $port, $timeout)
    {
        //Connect to the host on the specified port
        $smtpConnect  = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
        $smtpResponse = fgets($smtpConnect, 515);
        if (empty($smtpConnect)) {
            return "Failed to connect: $smtpResponse";
        }

        $logArray['connection'] = "Connected: $smtpResponse";

        //Request Auth Login
        $newLine =null;
        fwrite($smtpConnect, "AUTH LOGIN" . $newLine);
        $smtpResponse            = fgets($smtpConnect, 515);
        $logArray['authrequest'] = (string)$smtpResponse;

        //Send username
        $username =null;
        fwrite($smtpConnect, base64_encode($username) . $newLine);
        $smtpResponse             = fgets($smtpConnect, 515);
        $logArray['authusername'] = (string)$smtpResponse;

        //Send password
        $password =null;
        fwrite($smtpConnect, base64_encode($password) . $newLine);
        $smtpResponse             = fgets($smtpConnect, 515);
        $logArray['authpassword'] = (string)$smtpResponse;

        //Say Hello to SMTP
        $localhost =null;
        fwrite($smtpConnect, "HELO $localhost" . $newLine);
        $smtpResponse             = fgets($smtpConnect, 515);
        $logArray['heloresponse'] = (string)$smtpResponse;

        //Email From
        fwrite($smtpConnect, "MAIL FROM: $from" . $newLine);
        $smtpResponse                 = fgets($smtpConnect, 515);
        $logArray['mailfromresponse'] = (string)$smtpResponse;

        //Email To
        fwrite($smtpConnect, "RCPT TO: $to" . $newLine);
        $smtpResponse               = fgets($smtpConnect, 515);
        $logArray['mailtoresponse'] = (string)$smtpResponse;

        //The Email
        fwrite($smtpConnect, "DATA" . $newLine);
        $smtpResponse              = fgets($smtpConnect, 515);
        $logArray['data1response'] = (string)$smtpResponse;

        //Construct Headers
        $headers = "MIME-Version: 1.0" . $newLine;
        $headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
        $headers .= "To: $nameto <$to>" . $newLine;
        $headers .= "From: $namefrom <$from>" . $newLine;

        fwrite($smtpConnect, "To: $to\nFrom: $from\nSubject: $subject\n$headers\n\n$message\n.\n");
        $smtpResponse              = fgets($smtpConnect, 515);
        $logArray['data2response'] = (string)$smtpResponse;

        // Say Bye to SMTP
        fwrite($smtpConnect, "QUIT" . $newLine);
        $smtpResponse             = fgets($smtpConnect, 515);
        $logArray['quitresponse'] = (string)$smtpResponse;

        return true;
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $message
     *
     * @return bool
     */
    function SendEmail($from, $to, $subject, $message)
    {
        // mail($to , $subject, $content, "From: $from \n" ."MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1");
        $isentemail = mail($to, $subject, $message, "From: $from \n" . "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1");

        if ($isentemail) {
            echo 'Ja, email sent';
            return true;
        }

        echo 'Nein, email not sent';
        return false;
    }

}

