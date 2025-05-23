# ticketingevent-apps

ticketingevent-apps Website Projects

## Authors

-   [@ItsJuJu-Dev](https://github.com/ItsJuJu-Dev)
-   [@gekky27](https://github.com/gekky27)

## Deployment

To deploy this project run

-   copy .env and modify it

```bash
cp .env.example .env
```

-   install depedency

```bash
composer install
```

-   generate key

```bash
php artisan key:generate
```

-   create the symbolic link

```bash
php artisan storage:link
```

-   migrate database

```bash
php artisan migrate --seed
```

-   run

```bash
php artisan serve
```
