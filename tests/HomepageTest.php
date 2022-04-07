<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;
use Doctrine\ORM\EntityManagerInterface;

class HomepageTest extends WebTestCase
{

    private KernelBrowser $client;
    private EntityManagerInterface $em;
    static private string $now;
    static private string $nowCheck;
    static private string $volt;
    static private string $deviceAddress;

    const LOCATION = 'San Piero a Sieve';

    public static function setUpBeforeClass(): void
    {
        $now = new DateTime();
        self::$now = $now->format("Y-m-d H:i:s");
        self::$nowCheck = $now->format("d/m/Y H:i:s");
        self::$volt = "12";
        self::$deviceAddress = '55:44:33:22:11:AA';
    }
    protected function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager()
        ;
        $this->client = static::createClient();
    }
    private function getUserFromUsername(string $username)
    {

        $users = $this->em->createQueryBuilder()
                ->select('r')
                ->from('BiCoreBundle:Operatori', 'r')
                ->where('r.username = :username')
                ->setParameter('username', $username)
                ->getQuery()
                ->getResult();
        return $users[0];
    }
    private function getToken()
    {
        $username = $this->client->getContainer()->getParameter("bi_core.admin4test");
        $password = $this->client->getContainer()->getParameter("bi_core.adminpwd4test");
        $this->client->request('POST', '/api/login_check', [], [],
                ['CONTENT_TYPE' => 'application/json',
                    'Accept' => 'application/json'],
                json_encode(["username" => $username, "password" => $password])
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $response = $this->client->getResponse();

        $contenuto = json_decode($response->getContent());

        return $contenuto->token;
    }
    private function getAdminClient()
    {
        $user = $this->getUserFromUsername("admin");
        $this->client->loginUser($user);
    }
    public function testApiTokenWrongAuth()
    {
        $username = "test";
        $password = "test";
        $this->client->request('POST', '/api/login_check', [], [],
                ['CONTENT_TYPE' => 'application/json',
                    'Accept' => 'application/json'],
                json_encode(["username" => $username, "password" => $password])
        );

        $this->assertTrue($this->client->getResponse()->getStatusCode() == 401);
    }
    public function testApi(): void
    {
        $token = $this->getToken();
        $data = '{"device":"' . self::$deviceAddress . '","data":"' . self::$now . '","volt":"' . self::$volt . '","temp":"18","batteryperc":"100","longitude":"11.3447","latitude":"43.9614"}';
        $crawler = $this->client->request('PUT', '/api/volt/record.json', [], [], ['CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $token], $data);
        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($this->client->getResponse(), 200);
    }
    public function testDeviceList(): void
    {
        $this->getAdminClient();
        $crawler = $this->client->request('GET', '/Device/List', [], [], ['CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json']);
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent(), true);
        $check = $jsonResponse[0];
        $this->assertEquals($check["address"], self::$deviceAddress);
    }
    
    public function testDeviceLastLog(): void
    {
        $this->getAdminClient();
        $crawler = $this->client->request('POST', '/Log/Last/1', [], [], ['CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json']);
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent(), true);
        $this->assertEquals((float)$jsonResponse["volt"], (float)self::$volt);
        $this->assertEquals($jsonResponse["location"], self::LOCATION);
        $this->assertEquals($jsonResponse["address"], self::$deviceAddress);
        
    }    
    public function testDeviceLastWeekLog(): void
    {
        $this->getAdminClient();
        $crawler = $this->client->request('POST', '/Log/LastWeek/1', [], [], ['CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json']);
        $this->assertResponseIsSuccessful();
        /*$response = $this->client->getResponse();
        $jsonResponse = json_decode($response->getContent(), true);
        $check = $jsonResponse[0];
        $this->assertEquals((float)$check["volt"], (float)self::$volt);
        $this->assertEquals($check["location"], self::LOCATION);
        $this->assertEquals($check["address"], self::$deviceAddress);*/
        
    }
    
    public function testHomepage(): void
    {
        $this->getAdminClient();
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $responseText = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('root', $responseText);
    }
    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals(
                $statusCode, $response->getStatusCode(),
                $response->getContent()
        );
        $this->assertTrue(
                $response->headers->contains('Content-Type', 'application/json'),
                $response->headers
        );

        $jsonResponse = json_decode($response->getContent());
        $this->assertEquals(0, $jsonResponse->errcode);
        $this->assertEquals("OK", $jsonResponse->errmsg);
    }
}
