<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Mondestin
#[Route('/tasks')]
class TasksController extends AbstractController
{
    #[Route('/', name: 'app_tasks_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TasksRepository $tasksRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->add($task);
            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->add($task);
            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            try {
                $tasksRepository->remove($task);
                // show error message
                $this->addFlash('success', 'Tache supprimé avec succès');
            } catch (\Throwable $th) {
                // show error message
                $this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
            }
        }

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }
}
