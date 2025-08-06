<?php
declare(strict_types=1);

// Incluir el autoloader de Composer.
// La ruta es ahora "../vendor/autoload.php" porque main.php está en 'src'
// y vendor está un nivel arriba.
require_once __DIR__ . '/../vendor/autoload.php';

// Importar las clases de los repositorios y entidades que vamos a usar
use App\repositories\AuthorRepository;
use App\repositories\BookRepository;
use App\repositories\ArticleRepository;
use App\entities\Author; // Si quieres acceder a métodos específicos del autor
use App\entities\Book;   // Si quieres acceder a métodos específicos del libro
use App\entities\Article; // Si quieres acceder a métodos específicos del artículo

echo "<h1>Probando Repositorios</h1>";
echo "<hr>";

// --- Prueba de AuthorRepository ---
echo "<h2>1. Probando AuthorRepository::findAll()</h2>";
try {
    $authorRepo = new AuthorRepository();
    $authors = $authorRepo->findAll();

    if (empty($authors)) {
        echo "<p>No se encontraron autores en la base de datos.</p>";
    } else {
        echo "<h3>Autores encontrados:</h3>";
        echo "<ul>";
        foreach ($authors as $author) {
            // Asegúrate de que Author::getId(), getFirstName(), etc. sean públicos en Author.php
            if ($author instanceof Author) {
                echo "<li>ID: " . $author->getId() . " | Nombre: " . $author->getFirstName() . " " . $author->getSecondName() . " | Email: " . $author->getEmail() . "</li>";
            } else {
                echo "<li>Error: Objeto no es una instancia de Author.</li>";
            }
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error al probar AuthorRepository: " . $e->getMessage() . "</p>";
}
echo "<hr>";


// --- Prueba de BookRepository ---
echo "<h2>2. Probando BookRepository::findAll()</h2>";
try {
    $bookRepo = new BookRepository();
    $books = $bookRepo->findAll();

    if (empty($books)) {
        echo "<p>No se encontraron libros en la base de datos.</p>";
    } else {
        echo "<h3>Libros encontrados:</h3>";
        echo "<ul>";
        foreach ($books as $book) {
            // Asegúrate de que Book::getId(), getTitle(), etc. sean públicos en Book.php
            if ($book instanceof Book) {
                $authorName = "Desconocido";
                if ($book->getAuthor() instanceof Author) {
                    $authorName = $book->getAuthor()->getFirstName() . " " . $book->getAuthor()->getSecondName();
                }
                echo "<li>ID: " . $book->getId() . " | Título: " . $book->getTitle() . " | ISBN: " . $book->getIsbn() . " | Autor: " . $authorName . "</li>";
            } else {
                echo "<li>Error: Objeto no es una instancia de Book.</li>";
            }
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error al probar BookRepository: " . $e->getMessage() . "</p>";
}
echo "<hr>";


// --- Prueba de ArticleRepository ---
echo "<h2>3. Probando ArticleRepository::findAll()</h2>";
try {
    $articleRepo = new ArticleRepository();
    $articles = $articleRepo->findAll();

    if (empty($articles)) {
        echo "<p>No se encontraron artículos en la base de datos.</p>";
    } else {
        echo "<h3>Artículos encontrados:</h3>";
        echo "<ul>";
        foreach ($articles as $article) {
            // Asegúrate de que Article::getId(), getTitle(), etc. sean públicos en Article.php
            if ($article instanceof Article) {
                $authorName = "Desconocido";
                if ($article->getAuthor() instanceof Author) {
                    $authorName = $article->getAuthor()->getFirstName() . " " . $article->getAuthor()->getSecondName();
                }
                echo "<li>ID: " . $article->getId() . " | Título: " . $article->getTitle() . " | DOI: " . $article->getDOI() . " | Autor: " . $authorName . "</li>";
            } else {
                echo "<li>Error: Objeto no es una instancia de Article.</li>";
            }
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error al probar ArticleRepository: " . $e->getMessage() . "</p>";
}
echo "<hr>";

echo "<h2>Fin de las pruebas.</h2>";

?>