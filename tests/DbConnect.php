<?php

namespace tests;

use App\Repositories\LoansRepository;
use App\Database;

class DbConnect
{

	public $loansRepository = null;

	public function __construct()
	{
		$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $database = new Database(
            host: $_ENV['DB_HOST'],
            name: $_ENV['DB_NAME'],
            user: $_ENV['DB_USER'],
            password: $_ENV['DB_PASS']
        );
        
        $this->loansRepository = new LoansRepository($database);

	}

}