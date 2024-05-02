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

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

// Error traking
\Rollbar\Rollbar::init(
    [
        'access_token' => '598ecf0f1ba04e0f9b5a1a10090d06ed',
        // production, development
        'environment'  => 'development',
    ]
);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();

$builder = new ContainerBuilder;

$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();

$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->addBodyParsingMiddleware();

$app->addErrorMiddleware(true, true, true);

require APP_ROOT . '/config/routes.php';

$app->run();
