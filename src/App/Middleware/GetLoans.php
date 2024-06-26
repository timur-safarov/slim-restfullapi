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
use Slim\Routing\RouteContext;
use App\Repositories\LoansRepository;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\NotFoundException;

/**
 * GetLoans Class this is middleware for get data from current loan resource
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class GetLoans
{

    /**
     * Method __construct create new property - $repository to access the database
     *
     * @param $repository private LoansRepository
     */
    public function __construct(private LoansRepository $repository)
    {
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

        try {

            $context = RouteContext::fromRequest($request);

            $route = $context->getRoute();

            $id = $route->getArgument('id');

            $loan = $this->repository->getById((int) $id);

            // Выкидываем стандартный Exception о том что страница не найдена
            if ($loan === false) {
                \Rollbar\Rollbar::log(
                    \Rollbar\Payload\Level::ERROR, 'loan not found'
                );

                // Родной метод slim - он работает не корректно, убрал его
                // throw new HttpNotFoundException($request, message: 'not found');

                throw new \Exception(
                    message: json_encode( 
                        [
                            'message' => 'Loan not found',
                            'id' => $id
                        ]
                    )
                );

            }

        } catch(\Exception $e) {

            // Создадим атрибут с переменной loan
            // Потом в контроллере можно будет к ней обратиться
            $request = $request->withAttribute('loan', $loan);

            // Создаём объект responce
            $response = $handler->handle($request);

            // Заносим ошибку в responce
            $response->getBody()->write($e->getMessage());

            // Страница не найдена
            return $response->withStatus(404);

        }

        $request = $request->withAttribute('loan', $loan);

        return $handler->handle($request);

    }
}