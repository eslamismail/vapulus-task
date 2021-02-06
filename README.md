## About chat application

this is chat app build with laravel and with front spa nuxt js if you want to learn more for nuxt please visit <a href="https://nuxtjs.org/docs/2.x/get-started/installation">here</a> and if you want to learn more about laravel please visit <a href="https://laravel.com/docs/8.x">here</a>

## how to run application

- please run "git clone git@github.com:eslamismail/vapulus-task.git"
- please make sure docker is in your machine if you don't have it please install it from <a href="https://www.docker.com/products/docker-desktop">here </a>
- run "cd vapulus-task && docker-compose up --build -d"
- if this first run please run "docker exec -it user_service bash && php artisan migrate"
- lunching app on http::/localhost:3000

## explain how docker work

- run socket service on 6001
- run api on port 8000
- run front app on port 3000
- run phpmyadmin on port 8181

## notes

- please make sure for port 6001 not used on your machine
- please make sure for port 8000 not used on your machine
- please make sure for port 8181 not used on your machine
- please make sure for port 3000 not used on your machine
- please make sure for port 5000 not used on your machine
