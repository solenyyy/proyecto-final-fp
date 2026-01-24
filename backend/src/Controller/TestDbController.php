<?php
namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestDbController extends AbstractController
{
    #[Route('/test-db', name: 'test_db')]
    public function test(Connection $connection): Response
    {
        $db = $connection->executeQuery('SELECT DATABASE()')->fetchOne();
        return new Response('Conectado a la BDD: ' . $db);
    }
}
