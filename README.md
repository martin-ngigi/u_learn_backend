## Install laravel Admin.
- link to the admin documentation : https://laravel-admin.org/docs/en/
- in above link, click the world icon to change the language to English.
- To install laravel admin, run:
```
composer require encore/laravel-admin
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan admin:install
```


## solve admin problems
Link: https://www.dbestech.com/tutorials/laravel-admin-panel-config-problems-and-solutions

## Install sanctum package for authentication
```
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## APIs Controller:
```
php artisan make:controller API/UserController
php artisan make:controller API/CourseController
php artisan make:controller API/PayController 

```

## Create admin Controllers and link up with the related models: (RECOMMENDED WAY), rather than the above example
```
1. On MAC
php artisan admin:make TestController --model=App\\Models\\User
OR
2. On Windows
php artisan admin:make TestController --model=App\Models\User

php artisan admin:make UserController --model=App\\Models\\User
php artisan admin:make CourseController --model=App\\Models\\Course
php artisan admin:make CourseTypeController --model=App\\Models\\CourseType

```

## migration
- course_types is the table name
```
php artisan make:migration create_course_types_table
php artisan make:migration create_courses_table
```
- undo migration
```
php artisan migrate:rollback
```
## model and migrations
```
php artisan make:model CourseType -m
php artisan make:model Course -m
php artisan make:model Order -m
php artisan make:model MyStripeKeys -m

```

## run server globally
% php artisan serve --host=192.168.1.101 --
port=8000

## configure php to upload large files
- https://www.dbestech.com/tutorials/php-configuration-file-changes

## (In Mac) EDIT PHP INI file so as to enable posting large files
- In termial find php.ini file
```
php --ini
```
- Open php.ini file and edit it using vim
```
vim /opt/homebrew/etc/php/8.2/php.ini
```
- locate the following lines
```
post_max_size = 1024M                                                                                                            

upload_max_filesize = 1024M
```
- to search for post_max_size, press /post and hit enter
- edit by inserting, press i
- press escape button to exit editing
- to search for upload_max_filesize, press /upload and hit enter
- edit by inserting, press i
- press escape button to exit editing
- to save, press :wq

- Restart your php service. To restart make sure you have PHP-FPM. If not make sure you run
```
brew tap homebrew/services
```
- And then run the below command to make changes effective
```
brew services restart php@8.2
```

## (In Windows) EDIT PHP INI file so as to enable posting large files
- Open Xammp Server
- Beside Apache, click Config button
- Search for the following and update them
```
post_max_size = 1024M 
upload_max_filesize = 1024M 
``` 

- Save and restart the server.


### clear machine cache:
- in the terminal, run:
```
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan route:cache
php artisan config:cache
php artisan cache:clear
composer dump-autoload
php artisan view:clear
```

## Stripe payments
```
composer require stripe/stripe-php
```