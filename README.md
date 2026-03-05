# flea_market

## 環境構築

Dockerビルド
1. git clone https://github.com/tsugumi-0406/pro_test_flea_market<br>
2. cd pro_test_flea_market<br>
2. docker-compose up -d --build

Laravel 環境構築
1. docker-compose exec php bash
2. cp .env.example .env
3. mkdir -p storage/framework/{cache,data,sessions,views} bootstrap/cache
4. chmod -R 775 storage bootstrap/cache
5. composer install --no-scripts
6. composer install --no-scripts
7. php artisan key:generate
8. php artisan migrate
9. php artisan db:seed
10. php artisan storage:link
11. exit
12. docker-compose exec mysql mysql -uroot -p
13. root
14. CREATE DATABASE demo_test;

 
## URL
・開発環境 : http://localhost/<br>
・ユーザー登録 : http://localhost/register<br>
・phpMyAdmin : http://localhost:8080/ <br>
・メール認証 : http://localhost:8025/

## 使用技術
・PHP 8.1<br>
・Laravel 8.83.29<br>
・MySQL 8.0.26<br>
・nginx:1.21.1

## その他
環境変数を変更した場合（APP_URL 等）は、以下を実行してください。<br>
php artisan config:clear<br>
php artisan cache:clear<br>

登録されているユーザーの情報<br>
ユーザー名 : テスト1<br>
メールアドレス：aaa@gmail.com<br>
パスワード：password1<br>
CO01～CO05を出品している<br>

ユーザー名 : テスト2<br>
メールアドレス：bbb@gmail.com<br>
パスワード：password2<br>
CO06～CO010を出品している<br>

ユーザー名 : テスト3<br>
メールアドレス：ccc@gmail.com<br>
パスワード：password3<br>

db:seedで作成されます。


## ER図
![ER図](index.drawio.png)

# pro_test_flea_market
