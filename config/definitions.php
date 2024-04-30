<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Config
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
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
