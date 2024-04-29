<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class LoansRepository
{
    public function __construct(private Database $database)
    {
    }

    public function getAll(array $sort=[]): array
    {
        $pdo = $this->database->getConnection();

        // Сводим в нижний регистр
        $sort = array_map('mb_strtolower', array_map('trim', $sort));

        // Получаем сортировку по полю created_at
        $created_at = (array_key_exists('created_at', $sort) && in_array($sort['created_at'], ['asc', 'desc'])) ? $sort['created_at'] : 'asc';

        // Получаем сортировку по полю sum
        $sum = (array_key_exists('sum', $sort) && in_array($sort['sum'], ['asc', 'desc'])) ? $sort['sum'] : 'asc';

        $stmt = $pdo->query("SELECT * FROM loans ORDER BY created_at $created_at, sum $sum");
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|bool
    {
        $sql = 'SELECT * FROM loans WHERE id = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): string
    {
        $sql = 'INSERT INTO loans (fio, sum, created_at)
                VALUES (:fio, :sum, :created_at)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':fio', $data['fio'], PDO::PARAM_STR);
        $stmt->bindValue(':sum', $data['sum'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $data['created_at'], PDO::PARAM_INT);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $sql = 'UPDATE loans
                SET fio = :fio,
                    sum = :sum
                WHERE id = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':fio', $data['fio'], PDO::PARAM_STR);
        $stmt->bindValue(':sum', $data['sum'], PDO::PARAM_STR);
        
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = 'DELETE FROM loans WHERE id = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}