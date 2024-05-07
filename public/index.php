<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Main_File
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;

use App\Middleware\ErrorMiddleware;

use Slim\Middleware\RoutingMiddleware;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Error traking
\Rollbar\Rollbar::init(
    [
        'access_token' => $_ENV['ROLLBAR_TOKEN'],
        // production, development
        'environment'  => 'development',
    ]
);

$builder = new ContainerBuilder;

$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();

$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->addBodyParsingMiddleware();

// $app->addErrorMiddleware(true, true, true);

// Кастомим вывод ошибок под себя
$middleware = new ErrorMiddleware(
    $app->getCallableResolver(),
    $app->getResponseFactory(),
    false, // Прячем вывод ошибок
    false, // Прячем вывод ошибок
    false  // Прячем вывод ошибок
);



$app->add($middleware);


// $middleware = new RoutingMiddleware($app->getRouteResolver());
// $app->add($middleware);

require APP_ROOT . '/config/routes.php';

$app->run();
