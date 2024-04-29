<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;


class Home
{
    public function __construct(private PhpRenderer $view)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'home.php');
    }


    public function getRandomHex($num_bytes=4)
    {
        return bin2hex(openssl_random_pseudo_bytes($num_bytes));
    }

}