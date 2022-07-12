## **Welcome to the Symfony e-commerce template !**

**Requirements :**

- Symfony knowledge

- Bootstrap knowledge

- Mailtrap account

- Stripe account

- MySQL & Apache server

**6 steps before developping :**

1 - First clone this repository

2 - Run the project with symfony server:start

3 - Import all the dependencies with composer install

4 - Create a database with symfony console doctrine:database:create

5 - Make the migration if needed

6 - Modify DATABASE_URL, MAILER_DSN & STRIPE_SK in .env with your own database, SMTP settings & Stripe SK (secret key)

7 - Modify Checkout template with your own Stripe PK (public key)

Congratulation, your ready to develop !

Now your can register a new user and edit role to ROLE_ADMIN on phpMyAdmin to see the backoffice option in the navbar.

When adding new product be sure that all images have the same size otherwise retouch it.
  
This project use Bootswatch Lux theme, fill free to change it or just going back to the default Bootstrap theme in the base template.

**Advices for production :**

Server configuration :
- PHP 8.1
- Customize the connexion string
- Add the following code in a .htaccess file if needed :

<IfModule mod_rewrite.c>
	RewriteEngine On
    RewriteBase /
    RewriteCond %{THE_REQUEST} /public/([^\s?]*) [NC]
    RewriteRule ^ %1 [L,NE,R=302]
    RewriteRule ^((?!public/).*)$ public/$1 [L,NC]
</IfModule>

**How to improve this project :**
- Create a mailing service
- Create a cart service
- Create an uploader service