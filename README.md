Nozleq - Rezerwacja nocleg�w w Hotelu
========================

System zrobiony w celach edukacyjnych.

Instrukcja instalacji
--------------
* nale�y przej�� do katalogu nadzrz�dnego do katalogu w kt�m b�dzie instalowane oprogramowanie

```
git clone [�cie�ka do repozytorium]
cd nozleq
php composer.phar install
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```


