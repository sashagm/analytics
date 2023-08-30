<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">

<a href="https://packagist.org/packages/sashagm/analytics"><img src="https://img.shields.io/packagist/dt/sashagm/analytics" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/sashagm/analytics"><img src="https://img.shields.io/packagist/v/sashagm/analytics" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/sashagm/analytics"><img src="https://img.shields.io/packagist/l/sashagm/analytics" alt="License"></a>
<a href="https://packagist.org/packages/sashagm/analytics"><img src="https://img.shields.io/github/languages/code-size/sashagm/analytics" alt="Code size"></a>
<a href="https://packagist.org/packages/sashagm/analytics"><img src="https://img.shields.io/packagist/stars/sashagm/analytics" alt="Code size"></a>

[![PHP Version](https://img.shields.io/badge/PHP-%2B8-blue)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-%2B10-red)](https://laravel.com/)

</p>


## Laravel Analytics Unique Visitors and Views Middleware  

Наш пакет предоставляет middleware для подсчета уникальных просмотров и посетителей на страницах вашего Laravel приложения. Он может быть полезен для веб-мастеров, которые хотят отслеживать действия ваших пользователей на своем сайте. Middleware будут полностью контролировать любые запросы к серверу.


### Оглавление:

- [Требования](#требования)
- [Установка](#установка)
- [Использование](#использование)
    - [Время жизни](#время-жизни)
    - [Получения статистики](#получения-статистики)
    - [Кастомный логер](#кастомный-логер)
    - [Отслеживание](#отслеживание)
- [Дополнительные возможности](#дополнительные-возможности)
- [Тестирование](#тестирование)
- [Лицензия](#лицензия)


#### Требования

Основные требования для установки и корректной работы:

- `PHP` >= 8.0
- `Laravel` >= 10.x
- `Composer` >= 2.4.x

#### Установка

Для установки пакета необходимо выполнить команды:

- composer require sashagm/analytics
- php artisan analytics:install


#### Использование

Добавьте middleware `unique.views` и `unique.visitors` в маршруты, на которых хотите подсчитывать уникальные просмотры и посетителей:


```php
Route::get('/post/{id}', function ($id) {
    // ваш код
})->middleware(['unique.views', 'unique.visitors']);

```

Можно еще добавить так:


```php
Route::middleware(['unique.views', 'unique.visitors'])->group(function () {
    // Добавьте сюда ваши маршруты

    });

```

#### Время жизни
Вы можете настроить время хранения данных о просмотрах и посетителях, добавив следующие значения в файл `.env` вашего приложения:

```php

UNIQUE_ENABLED=true                 // Активировать работу
UNIQUE_VIEWS_TIME=60                // Время хранения данных о просмотрах (в минутах)
UNIQUE_VISITORS_TIME=1440           // Время хранения данных о посетителях (в минутах)
UNIQUE_LOGS=true                    // Логировать данные
UNIQUE_LOGS_DEFAULT_METHOD=true     // Использовать дефолтный вариант логирования(faalse - Кастомный логер)
UNIQUE_LOGS_PATH="logs/custom.log"  // Путь для кастомного логера
UNIQUE_ADMIN="admin.'"              // Какие имемованные маршруты необходимо исключить из учёта
UNIQUE_PROVIDER_USER="User"         // Как пометить пользователей
UNIQUE_PROVIDER_BOTS="Bots"         // Как пометить ботов/роботов/поисковые системы


```

#### Получения статистики

Этот метод будет возвращать коллекцию экземпляров модели `Statistic`, которые соответствуют указанной категории и были созданы за последние 7 дней. Вы можете использовать эту коллекцию для дальнейшей обработки данных статистики.

```php

$viewsLastWeek = Statistic::getLastWeek('route');

foreach ($viewsLastWeek as $statistic) {
    // Обработка данных статистики
}

```


Этот метод будет возвращать коллекцию экземпляров модели `Statistic`, которые соответствуют указанной категории и были созданы за последние 30 дней. Вы можете использовать эту коллекцию для дальнейшей обработки данных статистики.


```php
$category = 'example_category';
$statistics = Statistic::getLast30Days($category);

foreach ($statistics as $statistic) {
    // Обработка данных статистики
}
```

Этот метод будет возвращать коллекцию экземпляров модели `Statistic`, которые соответствуют указанной категории и были созданы за все время. Вы можете использовать эту коллекцию для дальнейшей обработки данных статистики.

```php
$category = 'example_category';
$statistics = Statistic::getAllTime($category);

foreach ($statistics as $statistic) {
    // Обработка данных статистики
}
```

#### Кастомный логер

Если вы не хотите использовать стандартный файл для логирования, вы можете использовать отдельный файл и записывать данные логов туда.
Просто измените путь до нового файла в `.env` параметр `UNIQUE_LOGS_PATH="logs/custom.log"`


#### Отслеживание

Чтобы понимать и различать пользователей от поисковых роботов вы можете указать разные префиксы.
Просто измените файл `.env` параметры `UNIQUE_PROVIDER_USER='User'` и `UNIQUE_PROVIDER_BOTS='Bots'`        

#### Дополнительные возможности

Наш пакет предоставляет ряд дополнительных возможностей, которые могут быть полезны при работе с темами:

- `php artisan analytics:install` - Данная команда установит все необходимые файлы пакета.


#### Тестирование

Вы можете запустить тесты для этого пакета, используя PHPUnit. Для этого выполните команду:


- `composer test`

#### Лицензия

Analytics - это программное обеспечение с открытым исходным кодом, лицензированное по [MIT license](LICENSE.md ).

