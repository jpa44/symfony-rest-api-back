# :zap: REST API with Symfony :zap:

### Heroku Stack

- App url: [https://fathomless-retreat-31414.herokuapp.com](https://fathomless-retreat-31414.herokuapp.com)
- Heroku MySQL 5.6 database : [cleardb](https://addons.heroku.com/cleardb)
- Heroku apache 2.4 : [heroku-apache2](https://devcenter.heroku.com/articles/php-support#web-servers)
- Heroku PHP 7.4 : [heroku-php-support](https://devcenter.heroku.com/articles/php-support#supported-versions)

---

### API Documentation

- [https://fathomless-retreat-31414.herokuapp.com/api/doc](https://fathomless-retreat-31414.herokuapp.com/api/doc)

---

### Run Project

#### Install Dependencies

`composer install --optimize-autoloader --prefer-dist`

#### Start local server

`symfony server:start`

clears and warmup/refresh cache

- `php bin/console cache:clear --no-warmup --env=dev`
- `php bin/console cache:warmup --env=dev` 

---

### Entities

---

### JWT token

- JWT authentification for API endpoints :  [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

---

### TODO

