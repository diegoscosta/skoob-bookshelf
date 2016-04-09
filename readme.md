Skoob Bookshelf
====
Biblioteca escrita em PHP para pegar livros de usuários no Skoob.

#####  Como Usar
Clone ou [baixe](https://github.com/diegoscosta/skoob-bookshelf/archive/master.zip) esse respositório
```ssh
git clone https://github.com/diegoscosta/skoob-bookshelf.git
```

Dentro do diretório baixado rode o [composer](https://getcomposer.org/)
```ssh
composer update
```
Em seu script PHP inclua o autoload gerado pelo composer
```php
<?php
require "vendor/autoload.php";
```

Instancie a classe Bookshelf e divirta-se.
```php
$books = new DiegoSCosta\Skoob\BookShelf();
$books->setUser($user_id);
$books->setShelf($books::SHELF_TODOS);

var_dump($books->getBooks());
```

##### Exemplo
Na branch `service` foi construído um serviço que retorna livros aleatórios usando como base o framework Silex.