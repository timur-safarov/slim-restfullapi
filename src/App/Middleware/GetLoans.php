<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use App\Repositories\LoansRepository;
use Slim\Exception\HttpNotFoundException;

class GetLoans
{
    public function __construct(private LoansRepository $repository)
    {
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();

        $id = $route->getArgument('id');

        $loan = $this->repository->getById((int) $id);
    
        // Выкидываем стандартный Exception о том что страница не найдена
        if ($loan === false) {
            throw new HttpNotFoundException($request, message: 'loan not found');
        }

        $request = $request->withAttribute('loan', $loan);

        return $handler->handle($request);
    }
}