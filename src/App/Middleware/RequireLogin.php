<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Middleware
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\ResponseFactory;
use App\Repositories\UserRepository;

/**
 * RequireLogin Class check if authorized you are
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class RequireLogin
{

    /**
     * Method __construct create new property - $repository to access the database
     *
     * @param $factory    private ResponseFactory
     * @param $repository private UserRepository
     */
    public function __construct(
        private ResponseFactory $factory,
        private UserRepository $repository
    ) {
    }

    /**
     * Method __invoke return class as a function
     *
     * @param $request Request
     * @param $handler RequestHandler
     * 
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (isset($_SESSION['user_id'])) {

            $user = $this->repository->find('id', $_SESSION['user_id']);

            if ($user) {

                $request = $request->withAttribute('user', $user);

                return $handler->handle($request);

            }

        }

        $response = $this->factory->createResponse();

        $response->getBody()->write('Unauthorised');

        \Rollbar\Rollbar::log(\Rollbar\Payload\Level::ERROR, 'Unauthorised');

        return $response->withStatus(401);
    }
}