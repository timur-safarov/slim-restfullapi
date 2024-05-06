# Slim PHP REST API Authentication, plus Web-based Signup and Login, plus Unit tests

# Задача:
Разработать REST API для управления займами.

* 1). Реализовать следующие API методы:
* POST /loans — создание нового займа.
* GET /loans/{id} — получение информации о займе.
* PUT /loans/{id} — обновление информации о займе.
* DELETE /loans/{id} — удаление займа.
* GET /loans — получение списка всех займов с базовыми фильтрами по дате создания и сумме.
* 2). Использовать любой PHP микрофреймворк (например, Lumen, Slim).
* 3). Настроить линтер для проверки стиля кода.
* 4). Настроить коллектор ошибок (например, Sentry, Rollbar).
* 5). Написать тесты, покрывающие основные кейсы использования API.
* 6). Cоздать README файл с инструкциями по запуску и использованию API.
* 7). Выложить проект на GitHub и развернуть на любой платформе хостинга(например, Heroku, AWS, Google Cloud, DigitalOcean, Yandex Cloud).

Ожидаемый результат:
- Рабочее API, соответствующее REST стандартам.
- Код без ошибок и предупреждений линтера.
- Тесты, покрывающие основные кейсы использования API.
- Наличие README.

/**=============================================================================**/

# Install
- Перед установкой у вас уже должен быть рабочий адрес вашего сайта и доступ к базе данных
- API тестировалось на версии PHP 8.3^ но 8.2 тоже должно подойти
- Также нужно установить git и composer
- Установить SSH keys если они не установлены на вашей машине
- Не забудьте включить модуль mod_rewrite
```sudo a2enmod rewrite```

```conf
<VirtualHost *:80>
    ServerName 185.38.84.49
    ServerAlias www.185.38.84.49
    DocumentRoot /var/www/html/
    ErrorLog /var/www/error.log
    CustomLog /var/www/custom_error.log combined

    # Включить mod_rewrite для директории
    <Directory /var/www/html>
        Options All
            AllowOverride All
        Order allow,deny
        Allow from all
            Require all granted
    </Directory>
</VirtualHost>
```

1). Склонируйте репозиторий проекта в корневую папку вашего сайта
```git clone git@github.com:timur-safarov/slim-restfullapi.git```

2). Залейте дамп (./database.sql) базы данных на свой сервер, предварительно создав пустую базу

	- После дампа на сайте уже будет аккаунт администратора
	- Login: admin@email.ru
	- Password: 123456

3). Переименуйте файл .env.example на .env
Откройте .env и поменяйте параметры для подключения к базе данных, если у вас другие
DB_HOST
DB_NAME
DB_USER
DB_PASS

**Остальные параметры вы можите сгенерировать заново, либо оставить как есть**

Сгенерировать HASH_SECRET_KEY через PHP скрипт
```php
echo hash_hmac('sha256', 'Lorem ipsum dolor sit amet', random_bytes(10));
```

Сгенерировать ENCRYPTION_KEY через консоль
Зайти в корень сайта и ввести
```php ./vendor/bin/generate-defuse-key```

Сгенерировать ENCRYPTION_KEY через PHP скрипт
```php
$key = Key::createNewRandomKey();
$storeMe = $key->saveToAsciiSafeString();
$key = Key::loadFromAsciiSafeString($storeMe);
echo $storeMe;
```

API_KEY будет доступен после регистрации - в вашем профиле

ROLLBAR_TOKEN - error трэкер.

Чтобы иметь доступ к дашборду с диаграммами - зарегистрируйтесь

https://rollbar.com/signup

И поменяйте ROLLBAR_TOKEN на свой

4). Если вам нужен ваш личный API_KEY для доступа к API, вам нужно зарегистрироваться на сайте.
Затем пройти в профиль аккаунта, там будет ваш API_KEY
Его просто нужно указать в файле .env

/**=============================================================================**/

# Работаем с API через POSTMAN

Во вкладке Authorization - вбиваем api-key
Указать key - X-API-Key
Указать value - 32486ac6397fdd13d5ef8b744852153e

После регистрации ваш API_KEY будет в вашем профиле или используйте тот что уже есть в файле .env

/**-----------------------------------------------------------------------------**/

1). Получение списка всех займов с базовыми фильтрами по дате создания и сумме

- GET /api/loans
- GET /api/loans?sort[created_at]=asc&sort[sum]=desc
  
  В headers
- key: Content-type
- value: application/json

/**-----------------------------------------------------------------------------**/

2). Создание нового займа

- POST /api/loans
- В body->raw: 
```json
{
	"fio": "Имя заёмщика",
	"sum": "1230"
}
```

В headers
- key: Content-type
- value: application/json

/**-----------------------------------------------------------------------------**/

3). Получение информации о займе
- GET /api/loans/{id}

В headers
- key: Content-type
- value: application/json

/**-----------------------------------------------------------------------------**/

4). Обновление информации о займе
- PUT /api/loans/{id}
- В body->raw: 
```json
{
	"fio": "Имя заёмщика",
	"sum": "1230"
}
```

В headers
- key: Content-type
- value: application/json

/**-----------------------------------------------------------------------------**/

5). Удаление займа
- DELETE /api/loans/{id}

В headers
- key: Content-type
- value: application/json

/**-----------------------------------------------------------------------------**/

# Описание заголовков

- 200 OK - удаление, обновление, вывод данных
- 201 Created - запись только что создана
- 304 Not Modified
- 400 Bad Request
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 405 NOT_ALLOWED - метод запрещён
- 422 Unprocessable Entity - переданы не верные данные для модели
- 500 Internal Server Error
- 502 Bad Gateway Host Not Found or connection failed

/**=============================================================================**/

# Прверка кода

- Запуск проверки на Code style

```./vendor/bin/phpcs ./config/```

```./vendor/bin/phpcs ./config/confg.php```

- Проверить все папки с Php скриптами

```./vendor/bin/phpcs --extensions=php src config public views tests```

- Запуск проверки на ошибки в коде

```./vendor/bin/phpcbf ./config/```

```./vendor/bin/phpcbf ./config/confg.php```

- Проверить все папки с Php скриптами

```./vendor/bin/phpcbf --extensions=php src config public views tests```

/**=============================================================================**/

# Unit тестирование
- Запускаем тесты

```./vendor/bin/phpunit tests```

/**=============================================================================**/

При использовании proxy или VPN на локальной машине может выдавать ошибку
502 Bad Gateway
Host Not Found or connection failed

/**=============================================================================**/