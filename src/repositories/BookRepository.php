<?php
declare(strict_types=1);

namespace App\repositories;
use App\entities\Author;
use App\repositories\AuthorRepository;
use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Book;
use PDO;

class BookRepository implements RepositoryInterface{
    private PDO $db;
    private AuthorRepository $authorRepo;


    public function __construct (){
        $this->db = Database::getConnection();
        $this->authorRepo = new AuthorRepository();
    }

    public function findAll(): array{//salida del sql
        $stmt = $this->db->query("CALL sp_book_list();");
        $rows=$stmt->fetchAll();
        $stmt->closeCursor();

        $out=[];
        foreach($rows as $r){
            $out[]=$this->hydrate($r);
        }
        return $out;
    }

    public function hydrate (array $row): Book{
    // Crea el objeto Author primero
    $Author = new Author(
        (int)$row['author_id'], // Usar author_id, no solo id
        $row['first_name'],
        $row['last_name'],
        $row['username'],
        $row['email'],
        $row['password'], // Pasar la contraseña directamente del array
        $row['orcid'],
        $row['afiliation']
    );

    // Tu código de Reflection ya no es necesario si la contraseña se pasa por el constructor
    // Si la contraseña ya está hasheada en la base de datos, no necesitas hacer nada aquí.

    // Devuelve el objeto Book, no el Author
    return new Book (
        (int)$row['publication_id'],
        $row['title'],
        $row['description'],
        new \DateTime($row['publication_date']),
        $Author, // Pasa el objeto Author completo
        $row['isbn'],
        $row['genre'],
        (int)$row['edition']
    );
}
    
    // probar crear una clase main.php se llama a authro y book repositories creamos una instancia y el metodo find all\ debug repository y usar findall para probar
    //articulo acabar el resto
    public function create(object $entity): bool{
        if(!$entity instanceof Book){
            throw new \InvalidArgumentException('Book expected');
        }

        $stmt = $this -> db -> prepare("CALL sp_create_book(
            :title,
            :description,
            :publication_date,
            :author_id,
            :isbn,
            :genre,
            :edition
        );");
        $ok = $stmt -> execute([
            ':title' => $entity -> getTitle(),
            ':description' => $entity -> getDescription(),
            ':publication_date' => $entity -> getPublicationDate(),
            ':author_id' => $entity -> getAuthor() -> getId(),
            ':isbn' => $entity -> getIsbn(),
            ':genre' => $entity -> getGenre(),
            ':edition' => $entity -> getEdition(),
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

        $stmt = $this -> db -> prepare("CALL sp_update_book(
            :id,
            :title,
            :description,
            :publication_date,
            :author_id,
            :isbn,
            :genre,
            :edition
        );");
        $ok = $stmt -> execute([
            'id'=> $entity -> getId(),
            ':title' => $entity -> getTitle(),
            ':description' => $entity -> getDescription(),
            ':publication_date' => $entity -> getPublicationDate(),
            ':author_id' => $entity -> getAuthor() -> getId(),
            ':isbn' => $entity -> getIsbn(),
            ':genre' => $entity -> getGenre(),
            ':edition' => $entity -> getEdition(),
        ]);

        if(!$ok){
            $stmt -> fetch();
        }
        $stmt -> closeCursor();
        return $ok;
        
    }

    public function delete(int $id): bool{
        $stmt = $this -> db -> prepare("CALL sp_delete_book(:id);");
        $ok = $stmt -> execute([':id' => $id]);
        if(!$ok){
            $stmt -> fetch();
        }
        $stmt -> closeCursor();
        return $ok;
        
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->db->prepare("CALL sp_find_book(:id)");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        $stmt->closeCursor();

        return $row ? $this->hydrate($row) : null;
    }


}
