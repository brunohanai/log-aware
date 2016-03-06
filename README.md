# LogAware

## 1. Instalação

O LogAware realiza três tarefas:

1. Monitora arquivos de texto (logs) esperando por novos registros;
2. Aplica busca por Regex nos novos registros;
3. Executa ações (envia e-mail, grava logs, envia mensagem no Slack) quando a busca encontra resultados.

### 1.1 Instalar Memcache Server

**Ubuntu:**
```
apt-get install memcached
service memcached start
```

**CentOS:**
```
yum install memcached
service memcached start
```

### 1.2 Instalar Memcache Client (PHP)

**Ubuntu** ( https://naveensnayak.wordpress.com/2013/07/24/installing-memcached-on-ubuntu/ ):
```
apt-get install php5-memcache
service apache2 restart
```

**CentOS:**
```
yum install php-pecl-memcache
service httpd restart
```

### 1.3 Instalar LogAware (stand-alone)

```
cd /var/www 
git clone https://github.com/brunohanai/log-aware.git
cd log-aware
composer install --no-dev
```

### 1.4 Criar o arquivo de configuração

```
vim log-aware-config.yml
```

```
log_aware:
  system:
    log_filepath: /var/log/log-aware.log
    log_level: debug
  actions:
    slack_test:
      type: slack
      options:
        webhook_url: https://hooks.slack.com/services/...
        channel: '#random'
        icon_emoji: :ghost:
        username: bot_name
    log_test:
      type: log
      options:
        filepath: /var/log/log-aware/log_test.log
        level: debug
    mail_test:
      type: mail
      options:
        subject: Subject
        to: to@mail.com
        from: from@mail.com
        host: smtp.mandrillapp.com
        port: 587
        username: user
        password: pass
  files:
    - filepath: /var/log/httpd/error.log
      filters:
        - description: Looking for Exception
          regex: /.*Exception.*/
          actions: [slack_test, mail_test]
        - description: Looking for ERROR
          regex: /.*ERROR.*/
          actions: [slack_test]
    - filepath: /var/log/httpd/access.log
      filters:
        - description: Looking for PUT
          regex: /.*PUT.*/
          actions: [slack_test_]
```

**Verificar se o aquivo de configuração está correto:**
```
php check.php log-aware-config.yml
```

### 1.5 Adicionar o script no CRON:

```
vim /etc/crontab (ou "crontab -e")
```

```
* * * * * root /usr/bin/php /var/www/log-aware/src/brunohanai/LogAware/Commands/LogAware.php /var/www/log-aware/log-aware-config.yml
```

**Verificar se o script está sendo executado:**

```
tail -f /var/log/cron
```

```
tail -f /var/log/log-aware.log
```

## 2. Sobre

### 2.1 Requisitos

* PHP 5+
* Linux

### 2.2 Bugs e melhorias

* Bugs e melhorias serão tratados [aqui pelo GitHub](https://github.com/brunohanai/log-aware/issues).


### 2.3 Autor

* Bruno Hanai