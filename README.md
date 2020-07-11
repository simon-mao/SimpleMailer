# Simple Mailer

## Usage

```shell script
composer require simonmao/simple-mailer
```

## Example

**Mail Config**

`config.yaml`

```yaml
email:
  debug: true
  host: smtpserver.com
  port: 587
  username: yourname@domain.com
  password: password
  encryption: tls # tls/ssl
  fromEmail: yourname@domain.com
  fromName: "Mailer"
```


**Load Config**

```yaml
$conf = Noodlehaus\Config::load('config.yaml')
    ->get('email');
```


**Initialization**

```php
$mailer = new \SimpleMailer\SimpleMailer($conf);
```


**Send Text Mail**

```php
$bool = $mailer->sendText('xxx@email.com', 'Title', 'hello');
var_dump($bool);
```


**Send HTML Mail**

```php
$bool = $mailer->sendHTML('xxx@email.com', 'Title', '<h1>hello</h1>');
var_dump($bool);
```

## Dependents

1. `phpmailer/phpmailer`