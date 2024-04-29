<?php


/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Config
 * @package    WordPress
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @version    1.2.18
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       ****
 * @since      1.0.0
 */

use App\Database;
use Slim\Views\PhpRenderer;

return [

    Database::class => function () {

        return new Database(
            host: $_ENV['DB_HOST'],
            name: $_ENV['DB_NAME'],
            user: $_ENV['DB_USER'],
            password: $_ENV['DB_PASS']
        );

    },

    PhpRenderer::class => function () {

        $renderer = new PhpRenderer(__DIR__ . '/../views');
        $renderer->setLayout('layout.php');

        return $renderer;
    }
];
