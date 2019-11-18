Условия задачи:

	На страницу вывести форму с полями input:email, select:категория из http://www.icndb.com/api/ При заполнении формы на емейл нужно отправить письмо с темой "Случайная шутка из %имя категории%"
	В теле письма должна быть случайная шутка из этой категории Эту же шутку нужно записать в файл на диске

Требования:

	Работу с API необходимо реализовать самому с использованием http://docs.guzzlephp.org/en/stable/
	Приложение должно быть на базе Symfony (3 или 4)
	Соответствие кода принципам SOLID
	Полное покрытие модульными и функциональными тестами с использованием phpunit

Рекомендуемые книги:

	Martin, Robert C. (2009). Clean Code: A Handbook of Agile Software Craftsmanship

	Principles of Package Design
	Preparing your code for reuse
	Matthias Noback

Установка:
    
```
git clone https://github.com/endemio/cv-test-jokes.git
cd ./cv-test-jokes
```

    !!! Заменить настройки в MAILER_URL на правильные
    
```
docker-compose up -d
docker-compose exec app bash
composer install
php bin/phpunit
```
    
    