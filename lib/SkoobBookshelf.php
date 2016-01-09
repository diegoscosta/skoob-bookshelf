<?php	
namespace DiegoSCosta\Skoob;

class SkoobBookshelf extends SkoobService
{
	private $user = null;
	private $shelf = null;

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
		$random_number = rand(1, count($books));

		return $books[$random_number];
	}

	public function getBooksStatus()
	{
		$status = $this->getSkoobContentFor('status');

		return $status->response;
	}

}

