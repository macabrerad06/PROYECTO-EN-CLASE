<?php
declare(strict_types=1);

namespace App\repositories;

use App\interfaces\RepositoryInterface;
use App\config\Database;
use App\entities\Author;
Use PDO;

class AuthorRepository implements RepositoryInterface{
    private PDO $db;

    public function __construct (){
        $this -> db = database::getConnection();
    }


    public function findAll(): array{//salida del sql
        $stmt = $this -> db -> query("SELECT * FROM author");
        $list=[];
        while ($row = $stmt->fetch()){
            $list[]=$this->hydrate($row);//row ->fila sql     devuelve como un array de author
        }
        return $list;

    }
    

    public function create(object $entity): bool{
        if(!$entity instanceof Author){//verificar si es una instancia de author
            throw new \InvalidArgumentException('Author expected');
        }
        $sql = "INSERT INTO author
        (id,first_name,second_name,username,email,password,orcid,afiliation) 
        VALUES(:id,:first_name,:second_name,:username,:email,:password,:orcid,:afiliation)";//para ingresar lso valores ponemos una referencia
        $stmt = $this -> db -> prepare($sql);
        return $stmt -> execute([
            'id' => $entity -> getId(),
            'first_name' => $entity -> getFirstName(),
            'second_name' => $entity -> getSecondName(),
            'username' => $entity -> getUsername(),
            'email' => $entity -> getEmail(),
            'password' => $entity -> getPassword(),
            'orcid' => $entity -> getOrcid(),
            'afiliation' => $entity -> getAfiliation()
        ]);
        
    }

    public function update(object $entity): bool{
        if(!$entity instanceof Author){
            throw new \InvalidArgumentException('Author expected');
        }
        $sql = "UPDATE author SET 
                first_name=:first_name,
                second_name=:second_name,
                username=:username,
                email=:email,
                password=:password,
                orcid=:orcid,
                afiliation=:afiliation WHERE id=:id";
        $stmt = $this -> db -> prepare($sql);//prepara la consulta
        return $stmt -> execute([
            'id' => $entity -> getId(),
            'first_name' => $entity -> getFirstName(),
            'second_name' => $entity -> getSecondName(),
            'username' => $entity -> getUsername(),
            'email' => $entity -> getEmail(),
            'password' => $entity -> getPassword(),
            'orcid' => $entity -> getOrcid(),
            'afiliation' => $entity -> getAfiliation()

        ]);
        
    }

       public function delete(int $id): bool{
        $sql = "DELETE FROM author WHERE id=:id";
        $stmt = $this -> db -> prepare($sql);
        return $stmt -> execute([
            ':id' => $id
        ]);
    }
    public function findByid(int $id): ?object{
        $sql = "SELECT * FROM author WHERE id=:id";
        $stmt = $this -> db -> prepare($sql);
        $stmt -> execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this -> hydrate($row) : null;

    }

    //convierte la fila sql o author 
    private function hydrate(array $row): Author{
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
    }

    
}

