<?php
declare(strict_types=1);

namespace App\repositories;
use App\entities\Author;
use App\interfaces\RepositoryInterface;
use App\config\Database;
use PDO;

class BookRepository implements RepositoryInterface{
    private PDO $db;
    private AuthorRepository $authorRepo;


    public function __construct (){
        $this -> db = database::getConnection();
        $this -> authorRepo = new AuthorRepository();
    }

    public function findAll(): array{//salida del sql   
        $stmt = $this -> db -> query("CALL sp_article_list();");
        $rows=$stmt->fetchAll();
        $stmt=CloseCursor();

        $out=[];
        foreach($rows as $r){
            $out[]=$this->hydrate($r);
        }
        return $out;
    }

    public function hydrate (array $row): Article{
                $Author = new Author(
            (int)$row['id'],
            $row['first_name'],
            $row['second_name'],
            $row['username'],
            $row['email'],
            'temporal',
            $row['orcid'],
            $row['afiliation']
        );
             //reemplazar hash de la contrasena
        $ref = new \ReflectionClass($Author);
        $prop =ref->getproperty('password');
        $prop->SetAccessible(true);
        $prop->setValue($Author,$row['password']);
        return $Author;

        return new Article (
            (int)$row['publication_id'],
            $row['title'],
            $row['description'],
            new \DateTime($row['publication_date']),
            $Author,
            $row['DOI'],
            $row['abstract'],
            $row['keywords'],
            $row['indexation'],
            $row['magazine'],
            $row['aknowledge_are']
        );
    }
    
    // probar crear una clase main.php se llama a authro y book repositories creamos una instancia y el metodo find all\ debug repository y usar findall para probar
    //articulo acabar el resto
    public function create(object $entity): bool{
        if(!$entity instanceof Book){
            throw new \InvalidArgumentException('Book expected');
        }

        $stmt = $this -> db -> prepare("CALL sp_create_Article(
            :title,
            :description,
            :publication_date,
            :author_id,
            :DOI,
            :abstract,
            :keywords,
            :indexation,
            :magazine,
            :aknowledge_are
        );");
        $ok = $stmt -> execute([
            ':title' => $entity -> getTitle(),
            ':description' => $entity -> getDescription(),
            ':publication_date' => $entity -> getPublicationDate(),
            ':author_id' => $entity -> getAuthor() -> getId(),
            ':DOI' => $entity -> getDOI(),
            ':abstract' => $entity -> getAbstract(),
            ':keywords' => $entity -> getKeywords(),
            ':indexation' => $entity -> getIndexation(),
            ':magazine' => $entity -> getMagazine(),
            ':aknowledge_are' => $entity -> getAknowledge_are(),
        ]);

        if(!$ok){
            $stmt -> fetch();
        }
        $stmt -> closeCursor();
        return $ok;
   
    }

    

    public function update(object $entity): bool{
        if(!$entity instanceof Book){
            throw new \InvalidArgumentException('Book expected');
        }
        $stmt = $this -> db -> prepare("CALL sp_update_Article(
            :id,
            :title,
            :description,
            :publication_date,
            :author_id,
            :DOI,
            :abstract,
            :keywords,
            :indexation,
            :magazine,
            :aknowledge_are
        );");
        $ok = $stmt -> execute([
            'id'=> $entity -> getId(),
            ':title' => $entity -> getTitle(),
            ':description' => $entity -> getDescription(),
            ':publication_date' => $entity -> getPublicationDate(),
            ':author_id' => $entity -> getAuthor() -> getId(),
            ':DOI' => $entity -> getDOI(),
            ':abstract' => $entity -> getAbstract(),
            ':keywords' => $entity -> getKeywords(),
            ':indexation' => $entity -> getIndexation(),
            ':magazine' => $entity -> getMagazine(),
            ':aknowledge_are' => $entity -> getAknowledge_are(),
        ]);

        if(!$ok){
            $stmt -> fetch();
        }
        $stmt -> closeCursor();
        return $ok;
        
    }

    public function delete(int $id): bool{
        $stmt = $this -> db -> prepare("CALL sp_delete_article(:id);");
        $ok = $stmt -> execute([':id' => $id]);
        if(!$ok){
            $stmt -> fetch();
        }
        $stmt -> closeCursor();
        return $ok;
        
        
    }
    public function findId(int $id): ?object{
        $stmt = $this -> db -> prepare("CALL sp_find_article(:id);");
        $stmt -> execute([':id' => $id]);
        $row = $stmt -> fetch();
        $stmt -> closeCursor();
        return $row ? $this -> hydrate($row) : null;
    }


}
