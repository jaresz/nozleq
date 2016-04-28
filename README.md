Nozleq - Rezerwacja noclegów w Hotelu
========================

System stworzony we frameworku Symfony 2.8 (w chwili tworzenia była to wersja "long-term support")

Wymagania
--------------
PHP co najmniej w wersji 5.5
Zalecane 5.6 lub 7.0

Instrukcja instalacji
--------------
### Przed instalacją
* należy przejść do katalogu nadzrzędnego do katalogu w którym będzie instalowane oprogramowanie
* stworzyć bazę danych - o dane dostępowe do niej zapyta się composer

### Instalacja
```shell
git clone https://github.com/jaresz/nozleq.git
cd nozleq
php composer.phar install
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```

API
--------------
### Przygotowanie

Uruchomienie lokalnego serwera PHP (to opcja, ale dalej zakładam lokalne adresy):
```shell
php app/console server:start
```

Dodanie klienta oAuth (zakres typów uprawnień można ograniczyć do potrzeb):
```shell
php app/console acme:oauth-server:client:create --redirect-uri="http://127.0.0.1:8000/" --grant-type="authorization_code" --grant-type="password" --grant-type="refresh_token" --grant-type="token" --grant-type="client_credentials"
```

Tokenu sesji pozyskujemy z adresu 
```shell
http://localhost:8000/app_dev.php/oauth/v2/token
```
metodą POST, podając
```
grant_type=password
client_id=CLIENT
client_secret=SECRET
username=admin
password=RazZimelen(ioWy)45!
```
gdzie 

password - to typ uwierzytelniania - nie należy tego zamieniać na swoje hasło - w tej formie ma być zapisane, 

CLIENT to numer klienta z tokenem np. 1_3q1qxsiq7q4g0g00cksg80goc44c4cogg8owkskcco4owcwss0

SECRET - wygenerowany poprzednim poleceniem - np. 5wcpu8aowls84wsgg0ccw08gk8cowssc8gcwsw0wsw8s8kkc4s

admin to nazwa użytkownika

RazZimelen(ioWy)45! to jego hasło

_Użytkownik musi mieć ROLE_ADMIN_

### Adresy RestAPI
```
Method	Path
GET	/rooms	- lista pokoi
GET	/rooms/{id}	- informacje o wybranym pokoju
POST	/rooms	- dodawanie nowego pokoju
DELETE	/rooms/{room}	- usuwanie pokoju
GET	/api/doc/{view}	- dokumentacja API
GET|POST	/oauth/v2/token	- pobieranie tokenu
```


