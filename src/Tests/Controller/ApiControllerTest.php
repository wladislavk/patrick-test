<?php
namespace Tests\Controller;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use GuzzleHttp\Client;

class ApiControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $parameters = [
        'db_driver' => 'pdo_sqlite',
        'db_path' => __DIR__ . '/../../../patrick_test.db',
        'isDevMode' => false,
    ];
    private $doctrinePaths = [
        __DIR__ . "/../../../doctrine",
    ];

    const SITE_URL = 'http://patrick.dev:8080';

    public function setUp()
    {
        $this->client = new Client();
        $connectionParams = [
            'driver' => $this->parameters['db_driver'],
            'path' => $this->parameters['db_path'],
        ];
        $connection = DriverManager::getConnection($connectionParams);
        $doctrineConfig = Setup::createXMLMetadataConfiguration($this->doctrinePaths, $this->parameters['isDevMode']);
        $this->entityManager = EntityManager::create($connection, $doctrineConfig);
    }

    public function testGetMessages()
    {
        $url = self::SITE_URL . '/api/get';
        $response = $this->client->request('GET', $url);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $messages = $data['messages'];
        $this->assertEquals(3, sizeof($messages));
        // test that data is ordered by date desc
        $this->assertEquals('message 1', $messages[2]['message']);
    }

    public function testSendRequest()
    {
        $url = self::SITE_URL . '/api/send';
        $requestData = [
            'message' => 'message 4',
        ];
        $response = $this->client->request('POST', $url, ['json' => $requestData]);
        $this->assertEquals(200, $response->getStatusCode());
        $message = $this->entityManager->getRepository('Entities\Message')
            ->findOneBy(['message' => 'message 4']);
        $this->assertNotNull($message);
        $this->entityManager->remove($message);
        $this->entityManager->flush();
    }
}
