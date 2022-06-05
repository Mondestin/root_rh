<?php

namespace App\Controller;

use App\Entity\Departments;
use App\Form\DepartmentsType;
use App\Repository\DepartmentsRepository;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// Mondestin
#[Route('/departments')]
class DepartmentsController extends AbstractController
{
    #[Route('/', name: 'app_departments_index', methods: ['GET'])]
    public function index(DepartmentsRepository $departmentsRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('departments/index.html.twig', [
            'departments' => $departmentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_departments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepartmentsRepository $departmentsRepository): Response
    {
        $department = new Departments();
        $form = $this->createForm(DepartmentsType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departmentsRepository->add($department);
            return $this->redirectToRoute('app_departments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('departments/new.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_departments_show', methods: ['GET'])]
    public function show(Departments $department): Response
    {
        return $this->render('departments/show.html.twig', [
            'department' => $department,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_departments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departments $department, DepartmentsRepository $departmentsRepository): Response
    {
        $form = $this->createForm(DepartmentsType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departmentsRepository->add($department);
            return $this->redirectToRoute('app_departments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('departments/edit.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_departments_delete', methods: ['POST'])]
    public function delete(Request $request, Departments $department, DepartmentsRepository $departmentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $department->getId(), $request->request->get('_token'))) {
            // Mondestin
            try {
                $departmentsRepository->remove($department);
                // show error message
                $this->addFlash('success', 'Département supprimé avec succès');
            } catch (\Throwable $th) {
                // show error message
                $this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
            }
        }

        return $this->redirectToRoute('app_departments_index', [], Response::HTTP_SEE_OTHER);
    }
}
