# Ações disponíveis

## SlackAction

Envia uma mensagem para o Slack (para um Channel ou Usuário).

Exemplo de configuração:

```
actions:
    slack_test:                                                 # Alterar
        type: slack
        options:
            webhook_url: https://hooks.slack.com/services/...   # Alterar
            channel: '#random'                                  # Alterar
            icon_emoji: :ghost:                                 # Alterar
            username: bot_name                                  # Alterar
```

## MailAction

Envia um e-mail.

Exemplo de configuração:

```
actions:
    mail_test:                          # Alterar
        type: mail
        options:
            subject: Assunto do e-mail  # Alterar
            to: to@mail.com             # Alterar
            from: fom@mail.com          # Alterar
            host: smtp.mandrillapp.com  # Alterar
            port: 587                   # Alterar
            username: user              # Alterar
            password: pass              # Alterar
```

## LogAction

Grava um arquivo de log.

Exemplo de configuração:

```
actions:
    log_test:                           # Alterar
        type: log 
        options:
            filepath: /var/log/file.log # Alterar
            level: debug                # Opções: 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'
```

# Utilizando as ações

## Configuração

Você pode configurar quantas ações quiser e deve nomeá-las para identificá-las.

Exemplo:

```
actions:
    acao_1:
        type: slack/mail/log
        options:
            ... # Opções específicas de acordo com o "type" escolhido
    acao_2:
        type: slack/mail/log
        options:
            ... # Opções específicas de acordo com o "type" escolhido
    acao_3:
        type: slack/mail/log
        options:
            ... # Opções específicas de acordo com o "type" escolhido
```

## Uso

Com a ação configurada, indique-a no filtro onde deve ser usada.

Exemplo:

```
files:
    - filepath: /var/log/httpd/error.log                       # Alterar
        filters:
            - description: Pegando os "ERROR" do arquivo.      # Alterar
              regex: /.*ERROR:.*/                              # Alterar
              actions: [acao_1, acao_2]                        # Alterar - Ações (nome) separadas por vírgula
        filters:
            - description: Pegando os "CRITICAL" do arquivo.   # Alterar
              regex: /.*CRITICAL:.*/                           # Alterar
              actions: [acao_3]                                # Alterar - Ações (nome) separadas por vírgula
    - filepath: /var/log/php.log                               # Alterar
        filters:
            - description: Pegando os "DEPRECATED" do arquivo. # Alterar
              regex: /.*DEPRECATED:.*/                         # Alterar
              actions: [acao_1]                                # Alterar
```