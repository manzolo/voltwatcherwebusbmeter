<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class HomepageTest extends WebTestCase
{

    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    private function getAdminClient()
    {
        $container = $this->client->getContainer();
        $session = $container->get('session');

        /* @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /* @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => 'admin'));
        $loginManager->loginUser($firewallName, $user);

        /* save the login token into the session and put it in a cookie */
        $container->get('session')->set('_security_' . $firewallName, serialize($container->get('security.token_storage')->getToken()));
        $container->get('session')->save();
        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
    }
    public function testHomepage(): void
    {
        $this->getAdminClient();
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('San Piero a Sieve', $crawler->html());
        //
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
    public function testApi(): void
    {
        $tokem = $this->getToken();
        $now = (new \DateTime())->format("Y-m-d H:i:s");
        $data = '{"device":"44:44:09:04:01:CC","data":"' . $now . '","volt":"12","temp":"18","batteryperc":"100","longitude":"11.3447","latitude":"43.9614"}';
        $crawler = $this->client->request('PUT', '/api/volt/record.json', [], [], ['CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $tokem], $data);

        $this->assertResponseIsSuccessful();
    }
}
