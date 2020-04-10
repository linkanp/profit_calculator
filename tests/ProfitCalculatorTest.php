<?php
namespace App\Tests;

use App\Controller\ProfitCalculator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfitCalculatorTest extends WebTestCase
{
    public function testShowCalculatorPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $crawler->filter('html h1.title:contains("Profit Calculator")')->count()
        );
        
    }
    public function testSubmitBuyAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Submit')->form();
        // set some values
        $form['action[action]'] = 'buy';
        $form['action[price]'] = 31;
        $form['action[quantity]'] = 10;
        // submit the form
        $crawler = $client->submit($form);
        $this->assertTrue(
            $client->getResponse()->isRedirect('/')
        );
        $crawler = $client->request('GET', '/');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Submitted successfully.")')->count()
        );
    }

    public function testSubmitSaleAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Submit')->form();
        // set some values
        $form['action[action]'] = 'sale';
        $form['action[price]'] = 40;
        $form['action[quantity]'] = 5;
        // submit the form
        $crawler = $client->submit($form);
        $this->assertTrue(
            $client->getResponse()->isRedirect('/')
        );
        $crawler = $client->request('GET', '/');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Submitted successfully.")')->count()
        );
    }
}