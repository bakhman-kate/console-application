Console Application
===============================

Установка
-------------------
Выполнить git clone https://github.com/bakhman-kate/console-application

В командной строке в корне проекта выполнить

composer install

yii migrate

Запуск
-------------------
В командной строке в корне проекта выполнить

yii task

Тестирование
-------------------
Создать БД yii2_basic_tests, содержащую данные для тестирования (настройки подключения к БД в /tests/codeception/config/congig.php).

В командной строке выполнить команду migrate из tests/codeception/bin/yii

Для запуска uniе тестов выполнить команду codecept run unit