<div style="text-align: center;">

![Logo](https://github.com/ancafe/calambre-back/blob/main/src/resources/img/logo.png?raw=true)

</div>


This repository it the backend part of a final Degree in Computer Engineering work vfor the Universidad Oberta de Catalunya. It's a tool to obtain meters of a certain electric supply which belongs to e-distribucion. With a clear commitment to the democratisation of energy as a basic necessity, and shared knowledge, this project serves as a basis for building a useful solution for the consumer in the future. Therefore, as many contributions and derivative projects are welcome.

<hr>

## Installation process

1. Execute the following commands:

```
mkdir calambre && cd calambre
git clone https://github.com/ancafe/calambre-back
cd calambre-back
cp src/.env.example src/.env
mkdir .docker/postgres-data
```

2. Edit the file `src/.env`


```
docker compose up -d --build
docker exec -it calambre-api bash
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
clear
php artisan IV:generate
```

3. Replace the printed line in the `.env` file (line 3)

```
...
APP_FIX_IV_FOR_EMAIL=**********************==
...
```

4. Create an example user:

```
curl --location --request POST 'http://calambre-nginx/api/register' --header 'Authorization: Bearer null' --form 'email="admin@calambre.localhost"' --form 'name="Demo User"' --form 'password="Demo1234"' --form 'password_confirmation="Demo1234"'; echo  
```

| User                     | Password |
|--------------------------|----------|
| admin@calambre.localhost | Demo1234 |
