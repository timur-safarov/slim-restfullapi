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

/**
 * UserRepository Class sql requests to the user table 
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class UserRepository
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
     * Method create create new row to the loan table
     *
     * @param $data array - the data for cteate new row
     * 
     * @return void
     */
    public function create(array $data): void
    {

        $pdo = $this->database->getConnection();

        $sql = 'INSERT INTO user (name, email, password_hash, api_key, api_key_hash)
                VALUES (:name, :email, :password_hash, :api_key, :api_key_hash)';

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password_hash', $data['password_hash']);
        $stmt->bindValue(':api_key', $data['api_key']);
        $stmt->bindValue(':api_key_hash', $data['api_key_hash']);

        $stmt->execute();

    }

    /**
     * Method find user by any field
     *
     * @param $column string - the name of field
     * @param $value  - the value of field
     * 
     * @return array|bool
     */
    public function find(string $column, $value): array|bool
    {
        $sql = "SELECT *
                FROM user
                WHERE $column = :value";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':value', $value);

        $stmt->execute();

        return $stmt->fetch();
    }
}