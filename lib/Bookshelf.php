<?php	
namespace DiegoSCosta\Skoob;

class Bookshelf extends Service
{
	private $user = null;
	private $shelf = null;

	const SHELF_TODOS = 0;
	const SHELF_LIDO = 1;
	const SHELF_LENDO = 2;
	const SHELF_QUERO_LER = 3;
	const SHELF_RELENDO = 4;
	const SHELF_ABANDONEI = 5;
	const SHELF_TENHO = 6;
	const SHELF_FAVOTIROS = 8;
	const SHELF_DESEJADOS = 9;
	const SHELF_TROCO = 10;
	const SHELF_EMPRESTADOS = 11;
	const SHELF_META = 12;


	public function setUser($user)
	{
		$this->user = $user;
		$this->api['user'] = sprintf("%s/user/%s", $this->url, $user);
		$this->api['status'] = sprintf("%s/bookcase_stats/books/%s", $this->url, $this->user);
	}

	public function setShelf($shelf)
	{
		if($this->user == null)
			return $this->setErrors('You must set your Skoob User');

		$this->shelf = $shelf;
		$this->api['books'] = sprintf("%s/bookcase/books/%s/shelf_id:%s/limit:36/", $this->url, $this->user, $shelf);
	}

	public function bookshelfExists()
	{
		$bookshelf = $this->getSkoobContentFor('books');

		return ($bookshelf->success) ? $bookshelf : $this->setErrors('Bookshelf not found', $bookshelf->cod_description);
	}

	public function getBooks()
	{
		$bookshelf = $this->bookshelfExists();

		if($bookshelf)
		{
			$bookshelfSize = $bookshelf->paging->total;
			$bookshelfPages = $bookshelf->paging->page_count;

			$books = $bookshelf->response;


			for($i = 2; $i <= $bookshelfPages; $i ++)
			{
				$nextBooks = $this->getSkoobContentFor('books', 'page:' . $i);
				$books = array_merge($books, $nextBooks->response);
			}

			return $books;
		}

		return $this->setErrors('Nenhum livro encontrado');
	}

	public function getRandomBook()
	{
		$books = $this->getBooks();
		$random_number = rand(0, count($books) - 1);

		return $books[$random_number];
	}

	public function getBooksStatus()
	{
		$status = $this->getSkoobContentFor('status');

		return $status->response;
	}

}

