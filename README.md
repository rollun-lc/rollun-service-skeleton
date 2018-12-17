
# rollun-service-skeleton

`rollun-service-skeleton` - скелет для построения сервисов на базе [rollun-expressive].
В `rollun-service-skeleton` изначально подключены такие модули:
* [rollun-com/rollun-datastore](https://github.com/rollun-com/rollun-datastore) - абстрактное хранилище данных;
* [rollun-com/rollun-permission](https://github.com/rollun-com/rollun-permission) - проверка прав доступа и OAuth аутентификация;
* [rollun-com/rollun-logger](https://github.com/rollun-com/rollun-logger) - логирование;
* [zendframework/zend-expressive-fastroute](https://github.com/zendframework/zend-expressive-fastroute) - рутизация;
* [zendframework/zend-servicemanager](https://github.com/zendframework/zend-servicemanager) - реализация PSR-11.

Для работы `rollun-com/rollun-datastore` и `rollun-com/rollun-permission` нужны таблицы в базе данных:
* [create_table_logs.sql](https://github.com/rollun-com/rollun-logger/blob/4.2.1/src/create_table_logs.sql)
* [acl.sql](https://github.com/rollun-com/rollun-permission/blob/4.0.0/src/Permission/src/acl.sql)

`rollun-service-skeleton` имеет несколько роутов по умолчанию:
* `/` - тестовый хендлер
* `/oauth/redirect` - редирект на гугл аутенфикацию
> Использовать `/oauth/redirect?action=login` для аутенфикации на логин, `/oauth/redirect?action=register` для 
аутенфикации на регистрацию.
* `/oauth/login` - роутинг на который гугл редиректит пользователя (при его успешной аутенфикации) для логина
* `/oauth/register` - роутинг на который гугл редиректит пользователя (при его успешной аутенфикации) для регистрации
* `/logout` - логаут пользователя
* `/api/datastore/{resourceName}[/{id}]` роутинг для доступу к абстрактному хранилищу, где `resourceName` название 
сервиса, а `id` - идентификатор записи.