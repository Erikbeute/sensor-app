<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SensorDataRepository;
use Symfony\Component\HttpFoundation\Response;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(SensorDataRepository $repo): Response
    {
        $data = $repo->findBy([], ['measuredAt' => 'DESC'], 50); 


        return $this->render('dashboard/index.html.twig', [
            'data' => $data,
        ]);
    }
}
