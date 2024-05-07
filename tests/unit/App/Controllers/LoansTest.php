<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   ApiTest
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

declare(strict_types=1);

namespace tests\unit\App\Controllers;

use \PHPUnit\Framework\TestCase;
use tests\Db;

/**
 * Database Class for Api Tests
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class LoansTest extends TestCase
{

    private $_db;
    private $_client;

    /**
     * Method setUp allow you to define instructions that will be executed before
     * 
     * @return void
     */
    protected function setUp(): void
    {

        $this->_db = new Db();

        $dataLoan = $this->_db->loansRepository->getAll(limit: 1);

        // Останавливаем все тесты в этом классе если нету данных
        if (count($dataLoan) <= 0) {
            exit('Данных для тестирования нету в базе - ' . __FILE__ . "\n");
        }


        // print_r($_SERVER);
        // echo 'http://'.$_SERVER["SERVER_NAME"];
        // die;


        $this->_client = new \GuzzleHttp\Client(
            [
                'base_uri' => 'http://'.$_ENV['HTTP_HOST'],
                'timeout'  => 10.0,
            ]
        );

    }

    /**
     * Method test for __invoke Method
     * 
     * @return void
     */
    public function testInvoke()
    {

        // Выбираем все записи в таблице
        $dataLoan = $this->_db->loansRepository->getAll();

        // Api запрос
        $response = $this->_client->request(
            'GET', 
            '/api/loans', 
            [
                'headers' => [
                    // 'User-Agent' => 'testing/1.0',
                    // 'debug'         => false,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'X-API-Key' => $_ENV['API_KEY'],
                ],
            ]
        );

        // Поверяем какой статус страницы
        $this->assertEquals(200, $response->getStatusCode());

        // Прверяем Content-Type
        $contentType = $response->getHeaders()['Content-Type'][0];

        $this->assertEquals('application/json', $contentType);

        // Получаем массив, который нам вернул Api
        $responseBody = json_decode($response->getBody(true)->getContents(), true);

        // Сверяем то что в базе и то что вернуло Api
        $this->assertEquals($dataLoan, $responseBody);

    }

    /**
     * Method test for create Method
     * 
     * @return void
     */
    public function testCreate()
    {

        // Api запрос
        $response = $this->_client->request(
            'POST', 
            '/api/loans',
            [
                'headers' => [
                    // 'User-Agent' => 'testing/1.0',
                    // 'debug'         => false,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'X-API-Key' => $_ENV['API_KEY'],
                ],
                'body' => json_encode(
                    [
                        'fio' => 'Тест Тест',
                        'sum' => 99999
                    ]
                ),
            ]
        );

        // Поверяем какой статус страницы
        $this->assertEquals(201, $response->getStatusCode());

        // Прверяем Content-Type
        $contentType = $response->getHeaders()['Content-Type'][0];

        $this->assertEquals('application/json', $contentType);

        // Получаем массив, который нам вернул Api
        $responseBody = json_decode($response->getBody(true)->getContents(), true);

        // Проверяем что новая запись создалась
        $this->assertIsInt((int)$responseBody['id']);

        // Удаляем запись так как она не нужна
        $this->_db->loansRepository->delete((int)$responseBody['id']);

    }

    /**
     * Method test for show Method
     * 
     * @return void
     */
    public function testShow()
    {

        // Выбираем одну запись в таблице
        $dataLoan = $this->_db->loansRepository->getAll(limit: 1)[0];

        // Api запрос
        $response = $this->_client->request(
            'GET',
            '/api/loans/'.$dataLoan['id'],
            [
                'headers' => [
                    // 'User-Agent' => 'testing/1.0',
                    // 'debug'         => false,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'X-API-Key' => $_ENV['API_KEY'],
                ],
            ]
        );

        // Поверяем какой статус страницы
        $this->assertEquals(200, $response->getStatusCode());

        // Прверяем Content-Type
        $contentType = $response->getHeaders()['Content-Type'][0];

        $this->assertEquals('application/json', $contentType);

        // Получаем массив, который нам вернул Api
        $responseBody = json_decode($response->getBody(true)->getContents(), true);

        // Сверяем то что в базе и то что вернуло Api
        $this->assertEquals($dataLoan, $responseBody);

    }

    /**
     * Method test for update Method
     * 
     * @return void
     */
    public function testUpdate()
    {

        // Создаём тестовую запись
        $dataLoan = [
            'fio' => 'Test Update',
            'sum' => 999,
            'created_at' => (string)strtotime('NOW'),
        ];

        $id = (int)$this->_db->loansRepository->create($dataLoan);

        $this->assertIsInt($id);

        // Забираем запись которую создали
        $dataLoan = $this->_db->loansRepository->getById($id);

        // Api запрос
        $response = $this->_client->request(
            'PUT',
            '/api/loans/'.$id,
            [
                'headers' => [
                    // 'User-Agent' => 'testing/1.0',
                    // 'debug'         => false,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'X-API-Key' => $_ENV['API_KEY'],
                ],
                'body' => json_encode(
                    [
                        'fio' => 'Тест Update Тест Update',
                        'sum' => random_int(9999, 99999)
                    ]
                ),
            ]
        );

        // Поверяем какой статус страницы
        $this->assertEquals(200, $response->getStatusCode());

        // Прверяем Content-Type
        $contentType = $response->getHeaders()['Content-Type'][0];

        $this->assertEquals('application/json', $contentType);

        // Получаем массив, который нам вернул Api
        $responseBody = json_decode($response->getBody(true)->getContents(), true);

        // Выбираем обновлённую запись
        $newDataLoan = $this->_db->loansRepository->getById($id);

        // Проверяем чтобы массивы были разные
        $this->assertNotEquals($dataLoan, $newDataLoan);

        // Удаляем запись так как она не нужна
        $this->_db->loansRepository->delete($id);

    }

    /**
     * Method test for delete Method
     * 
     * @return void
     */
    public function testDelete()
    {

        // Создаём тестовую запись
        $dataLoan = [
            'fio' => 'Test Delete',
            'sum' => 999,
            'created_at' => (string)strtotime('NOW'),
        ];

        $id = (int)$this->_db->loansRepository->create($dataLoan);

        $this->assertIsInt($id);

        // Api запрос
        $response = $this->_client->request(
            'DELETE',
            '/api/loans/'.$id, 
            [
                'headers' => [
                    // 'User-Agent' => 'testing/1.0',
                    // 'debug'         => false,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'X-API-Key' => $_ENV['API_KEY'],
                ]
            ]
        );

        // Поверяем какой статус страницы
        $this->assertEquals(200, $response->getStatusCode());

        // Прверяем Content-Type
        $contentType = $response->getHeaders()['Content-Type'][0];

        $this->assertEquals('application/json', $contentType);

        // Получаем массив, который нам вернул Api
        $responseBody = json_decode($response->getBody(true)->getContents(), true);

        // Выбираем удалённую запись
        $newDataLoan = $this->_db->loansRepository->getById($id);

        // Проверяем что массива данных нету
        $this->assertIsNotArray($newDataLoan);

        // Проверяем что запрос равен false
        $this->assertFalse($newDataLoan);

    }


}