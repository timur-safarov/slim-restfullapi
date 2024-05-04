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

namespace tests;

use App\Repositories\LoansRepository;
use App\Database;

/**
 * Database Class for connect to Database
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class Db
{

    public $loansRepository = null;

    /**
     * Method __construct create new properties for connected to Database
     */
    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $database = new Database(
            host: $_ENV['DB_HOST'],
            name: $_ENV['DB_NAME'],
            user: $_ENV['DB_USER'],
            password: $_ENV['DB_PASS']
        );

        $this->loansRepository = new LoansRepository($database);

    }

}