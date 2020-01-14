# README

## Installation

Ta ner sidan från github med

`git clone https://github.com/liiinder/ramverk1-project.git`

Installera sedan ramverket med `composer install`.

Se sen till att du har sqlite3 installerat.
Skapa databasen med

- `mkdir data`
- `chmod 777 data`
- `sqlite3 data/db.sqlite` kan behöva skriva något innan du stänger ner med ctrl d
- `chmod 666 data/db.sqlite`

Efter det skapas innehållet i databasen med `sqlite3 data/db.sqlite < sql/ddl/ddl_sqlite.sql`.

För att kunna få rätt index sida måste vi routea om indexsidan så funktionen indexAction läggs till.

Detta görs i vendor/anax/content/src/Content/FlatBasedContentController.php innuti klassen.

```
class FileBasedContentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * This is the index method action
     *
     * @return object
     */
    public function indexAction() : object
    {
        $this->di->get("response")->redirect("tag");
    }
```

Du skall nu ha en fungerande sida