Nozleq - Rezerwacja noclegów w Hotelu
========================

System zrobiony w celach edukacyjnych.

Instrukcja instalacji
--------------
* nale¿y przejœæ do katalogu nadzrzêdnego do katalogu w któm bêdzie instalowane oprogramowanie

```
git clone [œcie¿ka do repozytorium]
cd nozleq
php composer.phar install
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```


