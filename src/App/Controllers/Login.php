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
use App\Repositories\UserRepository;

/**
 * Login Class for authorization
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class Login
{

    /**
     * Method __construct create new properties and create rules of validate
     *
     * @param $view       private PhpRenderer
     * @param $repository private UserRepository
     */
    public function __construct(
        private PhpRenderer $view,
        private UserRepository $repository
    ) {
    }


    /**
     * GET /login
     * Форма авторизации
     * 
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function new(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'login.php');
    }

    /**
     * POST /login
     * Принимаем авторизационные данные после отправки формы
     * 
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $user = $this->repository->find('email', $data['email']);

        if ($user && password_verify($data['password'], $user['password_hash'])) {

            $_SESSION['user_id'] = $user['id'];

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);

        }

        return $this->view->render(
            $response, 
            'login.php', 
            [
                'data' => $data,
                'error' => 'Invalid login'
            ]
        );
    }

    /**
     * GET /logout
     * Удаление авторизыции и выход
     * 
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function destroy(Request $request, Response $response): Response
    {
        session_destroy();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}