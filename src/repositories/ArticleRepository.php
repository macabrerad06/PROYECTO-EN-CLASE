<?php
declare(strict_types=1);

namespace App\repositories;

use App\entities\Author;
use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Article; // Asegúrate de que esta línea esté presente y sea correcta

use PDO;
use ReflectionClass; // Importa ReflectionClass

class ArticleRepository implements RepositoryInterface
{
    private PDO $db;
    private AuthorRepository $authorRepo;

    public function __construct()
    {
        $this->db = Database::getConnection();
        $this->authorRepo = new AuthorRepository();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("CALL sp_article_list();");
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        $out = [];
        foreach ($rows as $r) {
            $out[] = $this->hydrate($r);
        }
        return $out;
    }

    // Renombrado de findId a findByid para cumplir con la interfaz
    public function findByid(int $id): ?object
    {
        $stmt = $this->db->prepare("CALL sp_find_article(:id);");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // Usar PDO::FETCH_ASSOC para obtener un array asociativo
        $stmt->closeCursor();
        return $row ? $this->hydrate($row) : null;
    }

    public function hydrate(array $row): Article
    {
        // Primero, hidrata el Author
        $author = new Author(
            (int)$row['author_id'], // Asume que el ID del autor está en 'author_id'
            $row['first_name'],
            $row['second_name'],
            $row['username'],
            $row['email'],
            'temporal', // Valor temporal para la contraseña, que se sobrescribirá
            $row['orcid'],
            $row['afiliation']
        );

        // Corrige el error de sintaxis de ReflectionClass y establece la contraseña
        $ref = new ReflectionClass($author);
        $prop = $ref->getProperty('password'); // Corregido: $ref->getProperty
        $prop->setAccessible(true);
        $prop->setValue($author, $row['password']);

        // Ahora, crea y devuelve el objeto Article
        return new Article(
            (int)$row['publication_id'],
            $row['title'],
            $row['description'],
            new \DateTime($row['publication_date']),
            $author, // Pasa el objeto Author ya hidratado
            $row['DOI'],
            $row['abstract'],
            $row['keywords'],
            $row['indexation'],
            $row['magazine'],
            $row['aknowledge_are']
        );
    }

    public function create(object $entity): bool
    {
        // Cambiado de Book a Article
        if (!$entity instanceof Article) {
            throw new \InvalidArgumentException('Article expected');
        }

        $stmt = $this->db->prepare("CALL sp_create_Article(
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
        $ok = $stmt->execute([
            ':title' => $entity->getTitle(),
            ':description' => $entity->getDescription(),
            ':publication_date' => $entity->getPublicationDate()->format('Y-m-d H:i:s'), // Formatea la fecha
            ':author_id' => $entity->getAuthor()->getId(),
            ':DOI' => $entity->getDOI(),
            ':abstract' => $entity->getAbstract(),
            ':keywords' => $entity->getKeywords(),
            ':indexation' => $entity->getIndexation(),
            ':magazine' => $entity->getMagazine(),
            ':aknowledge_are' => $entity->getAknowledge_are(),
        ]);

        if (!$ok) {
            // Considera loggear el error o lanzar una excepción más específica
            $errorInfo = $stmt->errorInfo();
            error_log("Error creating article: " . $errorInfo[2]);
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function update(object $entity): bool
    {
        // Cambiado de Book a Article
        if (!$entity instanceof Article) {
            throw new \InvalidArgumentException('Article expected');
        }
        $stmt = $this->db->prepare("CALL sp_update_Article(
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
        $ok = $stmt->execute([
            'id' => $entity->getId(),
            ':title' => $entity->getTitle(),
            ':description' => $entity->getDescription(),
            ':publication_date' => $entity->getPublicationDate()->format('Y-m-d H:i:s'), // Formatea la fecha
            ':author_id' => $entity->getAuthor()->getId(),
            ':DOI' => $entity->getDOI(),
            ':abstract' => $entity->getAbstract(),
            ':keywords' => $entity->getKeywords(),
            ':indexation' => $entity->getIndexation(),
            ':magazine' => $entity->getMagazine(),
            ':aknowledge_are' => $entity->getAknowledge_are(),
        ]);

        if (!$ok) {
            // Considera loggear el error o lanzar una excepción más específica
            $errorInfo = $stmt->errorInfo();
            error_log("Error updating article: " . $errorInfo[2]);
        }
        $stmt->closeCursor();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("CALL sp_delete_article(:id);");
        $ok = $stmt->execute([':id' => $id]);
        if (!$ok) {
            // Considera loggear el error o lanzar una excepción más específica
            $errorInfo = $stmt->errorInfo();
            error_log("Error deleting article: " . $errorInfo[2]);
        }
        $stmt->closeCursor();
        return $ok;
    }
}
