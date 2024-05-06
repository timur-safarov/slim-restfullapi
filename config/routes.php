<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   Config
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

use App\Controllers\Loans;
use App\Middleware\GetLoans;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\RequireAPIKey;
use App\Middleware\MethodNotAllowed;
use App\Controllers\Home;
use App\Middleware\AddJsonResponseHeader;
use App\Controllers\Signup;
use App\Controllers\Login;
use App\Middleware\ActivateSession;
use App\Controllers\Profile;
use App\Middleware\RequireLogin;

$app->group(
    '', function (RouteCollectorProxy $group) {

        $group->get('/', Home::class);

        $group->get('/signup', [Signup::class, 'new']);

        $group->post('/signup', [Signup::class, 'create']);

        $group->get('/signup/success', [Signup::class, 'success']);

        $group->get('/login', [Login::class, 'new']);

        $group->post('/login', [Login::class, 'create']);

        $group->get('/logout', [Login::class, 'destroy']);

        $group->get('/profile', [Profile::class, 'show'])
            ->add(RequireLogin::class);

    }
)->add(ActivateSession::class);

$app->group(
    '/api', function (RouteCollectorProxy $group) {

        // Тут вызываем класс через метод __invoke
        // Список всех заёмов
        $group->get('/loans', Loans::class);

        // Создание заёма
        $group->post('/loans', [Loans::class, 'create']);


        // GetLoans выбирает заём с текущим id
        $group->group(
            '', function (RouteCollectorProxy $group) {

                // GET /api/loans/{id} — получение информации о займе
                $group->get('/loans/{id:[0-9]+}', Loans::class . ':show');

                // PUT /api/loans — обновление информации о займе
                // Метод patch частично обновляет модель
                $group->put('/loans/{id:[0-9]+}', Loans::class . ':update');

                // DELETE /api/loans/{id} — удаление займа
                $group->delete('/loans/{id:[0-9]+}', Loans::class . ':delete');

            }
        )->add(GetLoans::class);

    }
)->add(RequireAPIKey::class)
  ->add(AddJsonResponseHeader::class);


  