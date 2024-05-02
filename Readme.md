Здравствуйте. Предлагаем пройти тестовое задание, в случае успешного выполнения готовы пригласить на собеседование с тимлидом.

Задача:
Разработать REST API для управления займами.

1. Реализовать следующие API методы:
POST /loans — создание нового займа.
GET /loans/{id} — получение информации о займе.
PUT /loans/{id} — обновление информации о займе.
DELETE /loans/{id} — удаление займа.
GET /loans — получение списка всех займов с базовыми фильтрами по дате создания и сумме.
2. Использовать любой PHP микрофреймворк (например, Lumen, Slim).
3. Настроить линтер для проверки стиля кода.
4. Настроить коллектор ошибок (например, Sentry, Rollbar).
5. Написать тесты, покрывающие основные кейсы использования API.
6. Cоздать README файл с инструкциями по запуску и использованию API.
7. Выложить проект на GitHub и развернуть на любой платформе хостинга(например, Heroku, AWS, Google Cloud, DigitalOcean, Yandex Cloud).

Ожидаемый результат:
- Рабочее API, соответствующее REST стандартам.
- Код без ошибок и предупреждений линтера.
- Тесты, покрывающие основные кейсы использования API.
- Наличие README.

/**====================================================================================================**/

composer create-project slim/slim-skeleton [my-app-name]

Совсем укороченная версия
composer create-project slim/slim [my-app-name]

Links
https://www.slimframework.com/
https://www.slimframework.com/docs/v4/

Rest
https://www.youtube.com/watch?v=FYQrMr7oDv0
https://www.youtube.com/watch?v=PHZtujcTRPk
https://www.youtube.com/watch?v=v5tAdjf0o3E
https://www.youtube.com/watch?v=DHUxnUX7Y2Y
https://prominado.ru/blog/razrabotka-restful-api/
https://www.simplifiedios.net/php-rest-api-example/
https://github.com/maurobonfietti/rest-api-slim-php/tree/master
https://www.simplifiedcoding.net/php-restful-api-framework-slim-tutorial-1/
https://www.phpflow.com/php/create-simple-rest-api-using-slim-framework/


То что нужно
https://github.com/daveh/slim-rest-api-example
https://www.youtube.com/watch?v=PHZtujcTRPk
Api key auth
https://www.youtube.com/watch?v=v5tAdjf0o3E
https://github.com/daveh/slim-api-auth-example


Тестирование
https://codex.so/phpunit
https://github.com/there4/slim-unit-testing-example

/**====================================================================================================**/

200 OK - вывод данных работает
201 Created - запись только что создана
304 Not Modified
400 Bad Request
401 Unauthorized
403 Forbidden
404 Not Found
405 NOT_ALLOWED
422 Unprocessable Entity
500 Internal Server Error
502 Bad Gateway Host Not Found or connection failed

/**====================================================================================================**/

HASH_SECRET_KEY
echo hash_hmac('sha256', 'The quick brown fox jumped over the lazy dog.', 'secret');

ENCRYPTION_KEY
Вот так правильно ENCRYPTION_KEY генерировать
$key = Key::createNewRandomKey();
$storeMe = $key->saveToAsciiSafeString();
$key = Key::loadFromAsciiSafeString($storeMe);

echo $storeMe;

Или войти в корень проекта и выполнить эту команду
php vendor/bin/generate-defuse-key

/**====================================================================================================**/

Работаем с API через консоль

С начала нужно установить пакет httpie
sudo apt install httpie

Работаем с API через POSTMAN

Для всех запросов нужно указать api-key
Во вкладке Authorization
Выбрать api-key
Указать key - X-API-Key
Указать value - ac0a79447e815c9c8ea2a2a2e5602b1c

Получение списка всех займов с базовыми фильтрами по дате создания и сумме
GET api/loans
GET api/loans?sort[created_at]=asc&sort[sum]=desc
В headers
key - Content-type
value - application/json

Создание нового займа.
POST api/loans
{
	"fio": "Иван Иваныч",
	"sum": "20202020"
}

GET api/loans/{id} — получение информации о займе


PUT api/loans/{id} — обновление информации о займе
{
    "fio": "Ниолай Иванович Говнюков 300",
    "sum": 1230
}


/**====================================================================================================**/

Запуск проверки на Code style
./vendor/bin/phpcs ./config/
./vendor/bin/phpcs ./config/confg.php

Запуск проверки на ошибки в коде
./vendor/bin/phpcbf ./config/
./vendor/bin/phpcbf ./config/confg.php

/**====================================================================================================**/

Unit тестирование
Запускаем тесты
./vendor/bin/phpunit tests