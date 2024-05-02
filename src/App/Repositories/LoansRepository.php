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
    public function getAll(array $sort=[], $limit = null): array
    {
        $pdo = $this->database->getConnection();

        // Сводим в нижний регистр
        $sort = array_map('mb_strtolower', array_map('trim', $sort));
        $sql_tail = [];

        foreach ($sort as $key => $value) {
            if (in_array($key, ['created_at', 'sum'])
                && in_array($sort[$key], ['asc', 'desc'])
            ) {
                // Получаем сортировку по полю created_at
                $sql_tail[] = "$key " . $sort['created_at'];
            }
        }

        // Получаем строку с сортировкой
        $sql_tail = ($sql_tail) ? ' ORDER BY ' . implode(',', $sql_tail) : '';

        if (is_numeric($limit) && $limit > 0) {
            $sql_tail .= ' limit ' . $limit;
        }
        
        // Так как у нас даты храняться в виде strtotime
        // используем DATE_FORMAT для приведения к дате
        $stmt = $pdo->query(
            "SELECT *, "
            . "DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%e %b %Y') AS created_at" 
            . " FROM loans" . $sql_tail
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

        // Так как у нас даты храняться в виде strtotime
        // используем DATE_FORMAT для приведения к дате
        $sql = 'SELECT *, '
            . " DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%e %b %Y') AS created_at "
            . ' FROM loans WHERE id = :id';

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