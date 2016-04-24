Nozleq - Rezerwacja noclegów w Hotelu
========================

System zrobiony w celach edukacyjnych.

Instrukcja instalacji
--------------
* należy przejść do katalogu nadzrzędnego do katalogu w któm będzie instalowane oprogramowanie

```
git clone https://github.com/jaresz/nozleq.git
cd nozleq
php composer.phar install
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```


