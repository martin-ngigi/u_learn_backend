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

## Controller:
```
php artisan make:controller API/UserController
php artisan make:controller ../../Admin/Controllers/CourseTypeController
```
## migration
- course_types is the table name
```
php artisan make:migration create_course_types_table
```
- undo migration
```
php artisan migrate:rollback
```
- model
```
php artisan make:model CourseType
```