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
use App\Repositories\LoansRepository;
use Valitron\Validator;

/**
 * Loans Class for get loans through api
 *
 * @category Class
 * @package  Framework_Slim
 * @author   Timur Safarov <tisafarov@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/timur-safarov/slim-restfullapi
 */
class Loans
{

    /**
     * Method __construct create new properties and create rules of validate
     *
     * @param $repository private LoansRepository
     * @param $validator  private Validator
     */
    public function __construct(
        private LoansRepository $repository,
        private Validator $validator
    ) {
        $this->validator->mapFieldsRules(
            [
                'fio' => ['required'],
                'sum' => ['required', 'numeric', ['min', 1]],
                'created_at' => ['required', 'numeric', ['lengthMin', 10]],
            ]
        );
    }

    /**
     * GET /api/loans
     * GET /api/loans?sort[created_at]=asc&sort[sum]=desc
     * Получение списка всех займов с базовыми фильтрами по дате создания и сумме
     * 
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {

        // Выбираем сортировку
        $sort = is_array($request->getQueryParams()['sort']) 
                ? $request->getQueryParams()['sort'] 
                : [];

        $data = $this->repository->getAll($sort);
    
        $body = json_encode($data);
    
        $response->getBody()->write($body);
    
        return $response;
    }

    /**
     * POST /api/loans
     * Создание нового займа
     * 
     * @param $request  Request
     * @param $response Response
     * 
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {

        $body = $request->getParsedBody();

        // Дату создания заёма
        $body['created_at'] = (string)strtotime('NOW');

        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {

            $response->getBody()->write(
                json_encode($this->validator->errors())
            );

            return $response->withStatus(422);

        }

        $id = $this->repository->create($body);

        $body = json_encode(
            [
                'message' => 'Loan created',
                'id' => $id
            ]
        );

        $response->getBody()->write($body);

        return $response->withStatus(201);
    }

    /**
     * // GET /api/loans/{id} — получение информации о займе
     * 
     * @param $request  Request
     * @param $response Response
     * @param $id       string
     * 
     * @return Response
     */
    public function show(Request $request, Response $response, string $id): Response
    {

        // Attribute с ключом loan создаётся в Middleware/GetLoans.php
        $loan = $request->getAttribute('loan');

        $body = json_encode($loan);
    
        $response->getBody()->write($body);
    
        return $response;        
    }

    /**
     * PUT /api/loans/{id}
     * обновление информации о займе
     * 
     * @param $request  Request
     * @param $response Response
     * @param $id       string
     * 
     * @return Response
     */
    public function update(
        Request $request,
        Response $response,
        string $id
    ): Response {

        $body = $request->getParsedBody();

        // Так как created_at на не нужно - убераем её из правил валидации модели
        $this->validator->reset();

        $this->validator->mapFieldsRules(
            [
                'fio' => ['required'],
                'sum' => ['required', 'numeric', ['min', 1]]
            ]
        );

        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {

            $response->getBody()->write(
                json_encode($this->validator->errors())
            );

            return $response->withStatus(422);

        }

        $rows = $this->repository->update((int) $id, $body);

        $body = json_encode(
            [
                'message' => 'Loan updated',
                'rows' => $rows
            ]
        );

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * DELETE /api/loans/{id} — удаление займа
     * 
     * @param $request  Request
     * @param $response Response
     * @param $id       string
     * 
     * @return Response
     */
    public function update(
        Request $request,
        Response $response,
        string $id
    ): Response {
        $rows = $this->repository->delete($id);

        $body = json_encode(
            [
                'message' => 'Loan deleted',
                'rows' => $rows
            ]
        );

        $response->getBody()->write($body);

        return $response;
    }


}