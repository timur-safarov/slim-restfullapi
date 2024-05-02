<?php

declare(strict_types=1);

namespace tests;

use \PHPUnit\Framework\TestCase;
use tests\DbConnect;

class UserAgentTest extends TestCase
{

    private $dbConnect;

    private function getData()
    {
        $this->dbConnect = new DbConnect();

        // Выбираем просто одну запись
        return $this->dbConnect->loansRepository->getAll(limit: 1);
    }


    public function testGet()
    {

        if (count($this->getData())<=0) {
            exit('Данных для тестирования нету в базе - ' . __FILE__ . "\n");
        }

        $loan = $this->getData()[0];

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://slim.local',
            'timeout'  => 10.0,
        ]);

        $response = $client->request('GET', '/api/loans/'.$loan['id'], [
            'headers' => [
                // 'User-Agent' => 'testing/1.0',
                // 'debug'         => false,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'X-API-Key' => $_ENV['API_KEY'],
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()['Content-Type'][0];

        $this->assertEquals('application/json', $contentType);

        $responseBody = json_decode($response->getBody(true)->getContents(), true);

        $this->assertEquals($loan, $responseBody);


        // $userAgent = json_decode($response->getBody())->{"user-agent"};

        // $this->assertRegexp('/Guzzle/', $userAgent);

    }



}