##Discover CSV

This is a small Laravel 5.4 program using consoletvs/charts to display a downloaded CSV file from Discover Card into a 
monthly summary for convenient perusal. Don't suck at life, just upload a simple file and enjoy.

###Pre-Install
In order to build this you will need composer version 

```Composer version 1.3.1```

and 

```NPM version 4.2.0```

###Install
Go ahead and run 

```>$ composer install ; npm install; npm run production```

This will install the dependencies and get the stylesheets up.

###Using SQLite
I made this to be able to use SQLite with Foreign Keys on. Simply touch a file in the database 
directory called 'database.sql'.

```[base]>$ cd database ; touch database.sql ; chmod 755 database.sql```

Add your discover CSV file to storage/app/public/ and from the base directory run
 
```php artisan storage:link ;```

###Finish Setup
Whether using SQLite, MYSQL, or PostgreSQL (setup your own connections) run

```php artisan migrate ; php artisan db:seed```

If you have any errors, let me know, this is not extremely well tested since it is basically a beginning test for 
another application I am building, but errors here will propagate there, so any input is welcome.

