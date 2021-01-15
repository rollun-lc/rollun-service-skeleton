
# rollun-service-skeleton

`rollun-service-skeleton` - скелет для построения сервисов на базе [zend-expressive](https://docs.zendframework.com/zend-expressive/).
В `rollun-service-skeleton` изначально подключены такие модули:
* [rollun-com/rollun-datastore](https://github.com/rollun-com/rollun-datastore) - абстрактное хранилище данных;
* [rollun-com/rollun-permission](https://github.com/rollun-com/rollun-permission) - проверка прав доступа и OAuth аутентификация;
* [rollun-com/rollun-logger](https://github.com/rollun-com/rollun-logger) - логирование;
* [zendframework/zend-expressive-fastroute](https://github.com/zendframework/zend-expressive-fastroute) - рутизация;
* [zendframework/zend-servicemanager](https://github.com/zendframework/zend-servicemanager) - реализация PSR-11.

`rollun-service-skeleton` имеет несколько роутов по умолчанию:
* `/` - тестовый хендлер
* `/oauth/redirect` - редирект на гугл аутентификацию
> Использовать `/oauth/redirect?action=login` для аутентификации на логин, `/oauth/redirect?action=register` для 
аутентификации на регистрацию.
* `/oauth/login` - роутинг на который google редиректит пользователя (при его успешной аутентификации) для логина
* `/oauth/register` - роутинг на который google редиректит пользователя (при его успешной аутентификации) для регистрации
* `/logout` - логаут пользователя
* `/api/datastore/{resourceName}[/{id}]` роутинг для доступу к абстрактному хранилищу, где `resourceName` название 
сервиса, а `id` - идентификатор записи.

### Установка

1. Установите зависимости.
    ```bash
    composer install
    ```

2. Для работы `rollun-com/rollun-datastore` и `rollun-com/rollun-permission` нужны таблицы в базе данных:
    * [create_table_logs.sql](https://github.com/rollun-com/rollun-logger/blob/4.2.1/src/create_table_logs.sql)
    * [acl.sql](https://github.com/rollun-com/rollun-permission/blob/4.0.0/src/Permission/src/acl.sql)
    
    Так же могут пригодиться настройки ACL по умолчанию: [acl_default.sql](/data/acl_default.sql).

3. Обязательные переменные окружения:
    * Для работы сервиса:
        - APP_ENV - возможные значения: `prod`, `test`, `dev`
        - APP_DEBUG - возможные значения: `true`, `false`
        - SERVICE_NAME - название сервиса
        - POD_NAME - уникальное имя сервиса
        
        (более детально [тут](https://github.com/rollun-com/all-standards/blob/master/docs/rsr/RSR_3.md))
    * Для БД:
        - DB_DRIVER (`Pdo_Mysql` - по умолчанию)
        - DB_NAME
        - DB_USER
        - DB_PASS
        - DB_HOST
        - DB_PORT (`3306` - по умолчанию)
    
    * Для аутентификации:
        - GOOGLE_CLIENT_SECRET - client_secret в личном кабинете google
        - GOOGLE_CLIENT_ID - client_id в личном кабинете google
        - GOOGLE_PROJECT_ID - project_id в личном кабинете google
        - HOST - домен сайт где происходит авторизация
        - EMAIL_FROM - от кого отправить email для подтверждения регистрации
        - EMAIL_TO - кому отправить email для подтверждения регистрации

4. Та же сразу с скелетоном поставляется php.ini и php-fpm.conf.

### Метрика
При обращении на `/api/webhook/cron` срабатывает механизм отправки метрики на `health-cheker`, если такой метрики нету, 
она будет создана автоматически. [Документация API метрик](https://docs.rollun.net/health-checker/).
Для собюытий типа php рантайм метрики и тд лучше использховать прометеус, с открытием ендпоинта /metrics
Во всех остальных случаях лучше использовать `health-checker`

### CI/CD

С скелетоном поставляется Dockerfile и настроеный CI/CD используя Github Actions и D2C WebHooks

CI/CD по умолчанию запускается по пушу в master ветку, 
это легко можно кастомизировать поменяв секцию `on` в [deploy.yaml](/.github/workflows/deploy.yml)
Так же нужно подбравить шаг `build` в [deploy.yaml](/.github/workflows/deploy.yml), там в коментах все указано

Для того, что бы CI/CD работал, необходимо добавить так называемые [секреты](https://docs.github.com/en/free-pro-team@latest/actions/reference/encrypted-secrets) в github репозиторий:

- D2C_DEPLOY_WEBHOOK - url для обновления Вашего контейнера в D2C [doc](https://docs.d2c.io/platform/webhooks/)
- DOCKER_USER - Ваше имя аккаунта в github.
- DOCKER_PASS - github token с правами на запись в packages [doc](https://docs.github.com/en/free-pro-team@latest/github/authenticating-to-github/creating-a-personal-access-token)

Для того, что бы использовать другой docker registry вместо github packages, нужно заменить `ghcr.io` на Ваш registry 
а DOCKER_USER и DOCKER_PASS поменять на ваш логин и пароль от registry

Схема названия докер образа - ghcr.io/rollun-com/**repo-name**/**repo-name**/image:latest

Перед пуллом, нужно залогинится с помошью docker login.

## Зависает установка зависимостей
Если у вас зависла установка зависимостей, то попробуйте поменять версии пакетов в composer.json на
```
"rollun-com/rollun-callback": "^5.0",
"rollun-com/rollun-logger": "^4.2",
```
а после установки вернуть прежние версии и запустить
```bash
composer update "rollun-com/*" --with-dependencies
```
