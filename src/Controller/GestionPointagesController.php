<?php

namespace App\Controller;

use App\Entity\Pointage;
use App\Form\GestionPointageType;
use App\Repository\PointageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//Mondestin
class GestionPointagesController extends AbstractController
{
    public function __construct(PointageRepository $repo, EntityManagerInterface $em)
    {

        $this->repo = $repo; //get the repository
        $this->em = $em; //set the entity manager

    }
    #[Route('/gestion/pointages', name: 'app_gestion_pointages')]
    public function index(Request $request): Response
    {
        // check if the user is connected 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        // get all the pointages
        $userChecks = $this->repo->findAll();

        $pointage = new Pointage();
        $form = $this->createForm(GestionPointageType::class, $pointage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get data that were submitted so that we can retrieve the id

            // setting the varaibles to be updated
            $comments = $form->get('comments')->getData();
            $pStatus =  $form->get('validate')->getData();
            $pId = 0;

            dd($form->getData());
            // update the pointage
            $this->repo->gestionPointage($pId, $comments, $pStatus);
            $this->em->flush();
            // show success message
            $this->addFlash('success', 'Vous avez bien mise à jour ce pointage');
            return $this->redirectToRoute('app_gestion_pointages');
        }
        return $this->renderForm('gestion_pointages/index.html.twig', [
            'form' => $form,
            'checks' => $userChecks,

        ]);
    }
    #[Route('/gestion/pointages/{id<[0-9]+>}/edit', name: 'app_gestion_pointages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pointage $pointage): Response
    {
        if ($this->isCsrfTokenValid('edit' . $pointage->getId(), $request->request->get('_token'))) {
            // get submitted data
            $pId = $request->get('id');
            $comments = $request->get('comments');
            $pStatus = $request->get('validate');
            // update the pointage
            $this->repo->gestionPointage($pId, $comments, $pStatus);
            $this->em->flush();
            // show success message
            $this->addFlash('success', 'Vous avez bien actualisé ce pointage');
            // redirect to the user pointage page
            return $this->redirectToRoute('app_gestion_pointages');
        }
        // wrong token do nothing
        $this->addFlash('error', 'Vous ne pouvez pas faire cette opération');
        return $this->redirectToRoute('app_gestion_pointages');
    }
}
