<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Database
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

namespace App;

use PDO;

/**
 * Database Class for connect to Database
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class Database
{

    /**
     * Method __construct create new properties for connected to Database
     *
     * @param $host     private string
     * @param $name     private string
     * @param $user     private string
     * @param $password private string
     */
    public function __construct(
        private string $host,
        private string $name,
        private string $user,
        private string $password
    ) {
    }

    /**
     * Method getConnection create connection to Database
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {

        $dsn = "mysql:host=$this->host;dbname=$this->name;charset=utf8";

        $pdo = new PDO(
            $dsn,
            $this->user,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

        return $pdo;
    }
}