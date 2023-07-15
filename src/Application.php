<?php

namespace VectorOop;

use Loader\DotEnvLoader;
use PDO;
use RuntimeException;

class Application
{
    private array $config;

    private PDO $connection;

    public function run(): void
    {
        $this->bootstrap();
        $this->loadConfig();
        $this->createDbConnection();
    }

    private function createDbConnection(): void
    {
        $host = $this->config['database']['host'];
        $db   = $this->config['database']['name'];
        $user = $this->config['database']['user'];
        $pass = $this->config['database']['password'];

        $dsn = "mysql:host=$host;dbname=$db";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->connection = new PDO($dsn, $user, $pass, $opt);
    }
    private function bootstrap(): void
    {
        (new DotEnvLoader())->load($this->getEnvDir());
    }
    private function loadConfig(): void
    {
        $configs = [];
        foreach (glob($this->getConfigDir() . '/*.php') as $filename) {
            $config = include $filename;
            if (false === is_array($config)) {
                throw new RuntimeException('Кофигурация должна содержать массив');
            }
            $configs[] = $config;
        }
        $this->config = array_merge($configs);
    }
    
    private function getConfigDir(): string
    {
        return __DIR__ . '/config';
    }

    private function getEnvDir(): string
    {
        return __DIR__ . '/../.env';
    }
}