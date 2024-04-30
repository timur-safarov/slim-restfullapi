<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Model
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

/**
 * LoansRepository Class sql requests to the loans table 
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class LoansRepository
{

    /**
     * Method __construct create new property - $database
     *
     * @param $database private Database
     */
    public function __construct(private Database $database)
    {
    }

    /**
     * Method getAll return all rows from loan table
     *
     * @param $sort array - sort data by fields created_at or sum
     * 
     * @return array
     */
    public function getAll(array $sort=[]): array
    {
        $pdo = $this->database->getConnection();

        // Сводим в нижний регистр
        $sort = array_map('mb_strtolower', array_map('trim', $sort));

        // Получаем сортировку по полю created_at
        $created_at = (
            array_key_exists('created_at', $sort) 
            && in_array($sort['created_at'], ['asc', 'desc'])
        ) ? $sort['created_at'] : 'asc';

        // Получаем сортировку по полю sum
        $sum = (
            array_key_exists('sum', $sort) 
            && in_array($sort['sum'], ['asc', 'desc'])
        ) ? $sort['sum'] : 'asc';

        $stmt = $pdo->query(
            "SELECT * FROM loans ORDER BY created_at $created_at, sum $sum"
        );
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method getById return row from loan table by id
     *
     * @param $id int - get data by this id
     * 
     * @return array
     */
    public function getById(int $id): array|bool
    {
        $sql = 'SELECT * FROM loans WHERE id = :id';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Method create create new row to the loan table
     *
     * @param $data array - the data for cteate new row
     * 
     * @return string
     */
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

    /**
     * Method update is change the row to the loan table by id
     *
     * @param $id   int - get row by this id
     * @param $data array - the data for change current row
     * 
     * @return int
     */
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

    /**
     * Method delete the row to the loan table by id
     *
     * @param $id int - get row by this id
     * 
     * @return int
     */
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