<?php
declare(strict_types=1);

namespace App\entities;
use DateTime;

class Article extends Publication{
    private string $DOI;
    private string $abstract;
    private string $keywords;
    private string $indexation;
    private string $magazine;
    private string $aknowledge_are;//es area

    public function __construct(int $id, string $title, string  $description,
                                \DateTime $publication_date, Author $author, 
                                string $DOI, string $abstract, string $keywords, 
                                string $indexation, string $magazine, string $aknowledge_are){
        
        //llamar  al constructor
        parent::__construct($id, $title, $description, $publication_date, $author);
        //inicializar los atributos propios
        $this-> DOI = $DOI;
        $this-> abstract = $abstract;
        $this-> keywords = $keywords;
        $this-> indexation = $indexation;
        $this-> magazine = $magazine;
        $this-> aknowledge_are = $aknowledge_are;
    }
    //getters y setters
    public function getDOI(): string{
        return $this-> DOI;
    }
    public function getAbstract(): string{
        return $this-> abstract;
    }
    public function getKeywords(): string{
        return $this-> keywords;
    }
    public function getIndexation(): string{
        return $this-> indexation;
    }
    public function getMagazine(): string{
        return $this-> magazine;
    }
    public function getAknowledge_are(): string{
        return $this-> aknowledge_are;
    }   
    public function setDOI(string $DOI): void{
        $this-> DOI = $DOI;
    }
    public function setAbstract(string $abstract): void{
        $this-> abstract = $abstract;
    }
    public function setKeywords(string $keywords): void{
        $this-> keywords = $keywords;
    }
    public function setIndexation(string $indexation): void{
        $this-> indexation = $indexation;
    }
    public function setMagazine(string $magazine): void{
        $this-> magazine = $magazine;
    }
    public function setAknowledge_are(string $aknowledge_are): void{
        $this-> aknowledge_are = $aknowledge_are;
    }
}
