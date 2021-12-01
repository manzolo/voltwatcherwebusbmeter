<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class HomepageTest extends WebTestCase
{

    private $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

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
        $now = (new \DateTime())->format("Y-m-d H:i:s");
        $data = '{"device":"44:44:09:04:01:CC","data":"' . $now . '","volt":"12","temp":"18","batteryperc":"100","longitude":"11.3447","latitude":"43.9614"}';
        $crawler = $this->client->request('PUT', '/api/volt/record.json', [], [], ['CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $token], $data);

        $this->assertResponseIsSuccessful();
    }
    public function testHomepage(): void
    {
        $this->getAdminClient();
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('San Piero a Sieve', $this->client->getResponse()->getContent());
        //$crawler->html()
    }
}
