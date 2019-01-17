<?php

namespace Telma\Selfcare\PrepaidBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testResetpassword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reset-mot-de-passe');
    }

}
