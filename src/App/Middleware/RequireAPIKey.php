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
 * RequireAPIKey Class verify do you have a correct api-key as a user
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class RequireAPIKey
{

    /**
     * Method __construct create new property - $factory and $repository
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
        // $params = $request->getQueryParams();

        // if (!array_key_exists('api-key', $params)) {
        if (!$request->hasHeader('X-API-Key')) {

            $response = $this->factory->createResponse();

            $response->getBody()->write(
                json_encode('api-key missing from request')
            );

            \Rollbar\Rollbar::log(
                \Rollbar\Payload\Level::ERROR,
                'api-key missing from request'
            );

            return $response->withStatus(400);

        }

        // if ($params['api-key'] !== 'abc123') {
        $api_key = $request->getHeaderLine('X-API-Key');

        $api_key_hash = hash_hmac('sha256', $api_key, $_ENV['HASH_SECRET_KEY']);

        $user = $this->repository->find('api_key_hash', $api_key_hash);

        if ($user === false) {

            $response = $this->factory->createResponse();

            $response->getBody()->write(
                json_encode('invalid API key')
            );

            \Rollbar\Rollbar::log(\Rollbar\Payload\Level::ERROR, 'invalid API key');

            return $response->withStatus(401);

        }

        $response = $handler->handle($request);

        return $response;
    }
}