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
use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

/**
 * Profile Class Where you can get your's Api-key
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class Profile
{
    /**
     * Method __construct create new properties and create rules of validate
     *
     * @param $view private PhpRenderer
     */
    public function __construct(private PhpRenderer $view)
    {
    }

    /**
     * GET /profile
     * Выводим Api-key
     * 
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function show(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');

        $encryption_key = Key::loadFromAsciiSafeString($_ENV['ENCRYPTION_KEY']);

        $api_key = Crypto::decrypt($user['api_key'], $encryption_key);

        return $this->view->render(
            $response,
            'profile.php',
            [
                'api_key' => $api_key
            ]
        );
    }
}