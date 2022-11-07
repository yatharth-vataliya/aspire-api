# Steps to set up and use this APP

> First of all clone repo from below mentioned link

```shell
git clone https://github.com/yatharth-vataliya/aspire-api
````
> Then go to root director of project and run following command in terminal (Assuming you are using Ubuntu or Linux os)

```shell
composer install
cp .env.example .env
php artisan key:generate
```
> Then go to .env file and set up APP_TIMEZONE, APP_NAME, DB_HOST, DB_DATABASE, DB_USERNAME and DB_PASSWORD as you preferred.

> after setting database credentials run following command
```shell
php artisan migrate:fresh --seed
php artisan serve
```
> Now app is running

> It will create database tables for you and generate 2 dummy customer user and 1 admin user.

> Then open this postman link `https://www.postman.com/security-technologist-1642160/workspace/aspire-work` it contains all necessary API endpoints. And you can direct use through web browser also but you want to use PC app then just follow below mentioned steps.

> Now you can login through dummy customer user and password and password is `password` and you get a `accessToken` in response so save that to anywhere else and go to above mentioned postman link and `export aspire-api collection` and it will download file in your PC. 

> Then open postman client in your pc locally and import that collections into your postman client.

> Then create one environment for customer user and other environment for admin user.

> Then create two global variable in both environment as following 
> 1. `host` and value is `localhost:8000/api`
> 2. Now call `Register User` API and fill data as you want then call it. In repose you will get `accessToken` then you have to set that token as  `token` variable and you have to do same process for getting admin token but with different api call `Login (Get Admin access Token)` and email for admin is `admin@gmail.com` and password is `password`.

> Note :- you have to create new admin by yourself then just run following command
```shell
php artisan CreateAdminUser
```
> Now you can call `Create Loan` end-point and fill data as you want.

> Then switch to Admin user environment and call `Get All Loan` end-point and you will see list of loans that customer created. And pick a loan id that you wish to approve.

> Then call `Approve Loan` end-point with loan id and fill data as you want.

> Then again switch to customer user environment and call `Repay Loan Term` with approved loan id to pay weekly payment.

---

# For testing

> Run following command
```shell
cp .env .env.testing
```
> and fill APP_TIMEZONE, APP_NAME, DB_HOST, DB_DATABASE, DB_USERNAME and DB_PASSWORD as you preferred but it will used for testing only. If you don't create this file then original .env file used as testing file.

> Then just run following command

```shell
php artisan test
```
