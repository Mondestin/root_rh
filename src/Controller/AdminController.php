<?php

namespace App\Controller;

use App\Repository\DashboardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(DashboardRepository $dashboarRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dashboard = $dashboarRepository->find(0);
        return $this->render('home/index.html.twig', [
            'dashboard' => $dashboard
        ]);
    }
}
