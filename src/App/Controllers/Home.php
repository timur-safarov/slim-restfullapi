<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Controller
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use \Rollbar\Rollbar;
use \Rollbar\Payload\Level;

/**
 * Home Class for main page
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class Home
{

    /**
     * Method __construct create new property - $view
     *
     * @param $view private PhpRenderer
     */
    public function __construct(private PhpRenderer $view)
    {
    }

    /**
     * Method __invoke return class as a function
     *
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {

        // Rollbar::log(Level::INFO, 'Test info message');
        
        try {
            throw new \Exception('Test exception');
        } catch (\Exception $e) {
            \Sentry\captureException($e);
        }

        return $this->view->render($response, 'home.php');
    }

    /**
     * Method __construct createnew property - $view
     *
     * @param $num_bytes integer
     * 
     * @return string
     */
    public function getRandomHex($num_bytes=4)
    {
        return bin2hex(openssl_random_pseudo_bytes($num_bytes));
    }

}