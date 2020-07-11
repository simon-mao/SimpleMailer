<?php
/**
 * Created by PhpStorm.
 * User: Simon Mao
 * Date: 2020/7/11
 * Time: 7:27 下午
 */

require_once 'vendor/autoload.php';

$conf = Noodlehaus\Config::load('config.yaml')
    ->get('email');
$mailer = new \SimpleMailer\SimpleMailer($conf);

$bool = $mailer->sendText('xxx@email.com', 'Title', 'hello');
var_dump($bool);

$bool = $mailer->sendHTML('xxx@email.com', 'Title', '<h1>hello</h1>');
var_dump($bool);