<?php
/**
 * Created by PhpStorm.
 * User: Simon Mao
 * Date: 2020/7/11
 * Time: 6:04 下午
 */

namespace SimpleMailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class SimpleMailer
 * @package SimpleMailer
 */
class SimpleMailer {

    private $debug = false;
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;
    private $fromEmail;
    private $fromName;

    /**
     * SimpleMailer constructor.
     * @param $conf
     */
    public function __construct($conf) {
        foreach ($conf as $key => $value) $this->$key = $value;
    }

    /**
     * @param $debug
     * @return $this
     */
    public function setDebug($debug) {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param $host
     * @return $this
     */
    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    /**
     * @param $port
     * @return $this
     */
    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @param $encryption
     * @return $this
     */
    public function setEncryption($encryption) {
        $this->encryption = $encryption;
        return $this;
    }

    /**
     * @param $fromEmail
     * @return $this
     */
    public function setFromEmail($fromEmail) {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * @param $fromName
     * @return $this
     */
    public function setFromName($fromName) {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * 发送文本邮件
     * @param       $address
     * @param       $subject
     * @param       $body
     * @param array $files
     * @return bool
     * @throws \Exception
     */
    public function sendText($address, $subject, $body, $files = []) {
        return $this->sendEmail($address, $subject, $body, $files, false);
    }

    /**
     * 发送 HTML 邮件
     * @param       $address
     * @param       $subject
     * @param       $body
     * @param array $files
     * @return bool
     * @throws \Exception
     */
    public function sendHTML($address, $subject, $body, $files = []) {
        return $this->sendEmail($address, $subject, $body, $files, true);
    }

    /**
     * 发送邮件
     * @param       $address
     * @param       $subject
     * @param       $body
     * @param array $files
     * @param bool  $isHTML
     * @return bool
     * @throws \Exception
     */
    private function sendEmail($address, $subject, $body, $files = [], $isHTML = false) {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            throw new \Exception("PHPMailer not exists.");
        }

        $mail          = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        try {
            // 服务设置
            if ($this->debug) $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->Port       = $this->port;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->username;
            $mail->Password   = $this->password;
            $mail->SMTPSecure = $this->encryption;

            // 设置发件人信息
            $mail->setFrom($this->fromEmail, $this->fromName);

            // 收件人
            if (is_array($address)) { // 多个收件人
                $address = array_unique($address);
                foreach ($address as $addr) {
                    if (!empty($addr)) {
                        $addr = trim($addr);
                        $mail->addBCC($addr);
                    }
                }
            } else { // 单个收件人
                $mail->addAddress($address);
            }

            // 附件
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $mail->addAttachment($file, basename($file));
                    }
                }
            }

            // 内容
            $mail->Subject = '=?utf-8?B?' . base64_encode($subject) . '?=';
            $mail->isHTML($isHTML);
            $mail->Body = $body;
            $mail->send();
        } catch (Exception $e) {
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
        return true;
    }

}