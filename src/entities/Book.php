<?php

declare(strict_types=1);

namespace App\entities;
use DateTime;

class Book extends Publication {
private string $isbn;
private string $genre;
private int $edition;

public function __construct(int $id, string $title, string $description, 
                            \DateTime $publication_date, Author $author, string $isbn, 
                            string $genre, int $edition){
   //llamr al constructor de la clase padre
   parent::__construct($id, $title, $description, $publication_date, $author);
   //inicializamos los atributos propios
   
    $this-> isbn = $isbn;
    $this-> genre = $genre;
    $this-> edition = $edition;
    }
    //getters y setters 
    public function getIsbn(): string{
        return $this-> isbn;
    }
    public function getGenre(): string{
        return $this-> genre;
    }
    public function getEdition(): int{
        return $this-> edition;
    }
    public function setIsbn(string $isbn): void{
        $this-> isbn = $isbn;
    }
    public function setGenre(string $genre): void{
        $this-> genre = $genre;
    }
    public function setEdition(int $edition): void{
        $this-> edition = $edition;
    }
}