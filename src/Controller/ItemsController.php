<?php

namespace App\Controller;

use App\Entity\Items;
use App\Entity\Tasks;
use App\Form\ItemsType;
use App\Repository\ItemsRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Mondestin
#[Route('/items')]
class ItemsController extends AbstractController
{
    private $em; //define an entity manager variable 
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em; //set the entity manager
    }
    #[Route('/{task_id}', name: 'app_items_index', methods: ['GET'])]
    public function index(Request $request, ItemsRepository $itemsRepository, TasksRepository $trepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // get task id
        $task_id = $request->get('task_id');
        // get task info
        $task = $this->em->find(Tasks::class, $task_id);
        // get link items
        $items = $itemsRepository->getLinkedItems($task_id);
        $item_done = $itemsRepository->getLinkedItemsDone($task_id);
        $total = count($items);
        $item_done_count = count($item_done);
        $progress = 0;
        if ($total > 0) {
            $progress = ($item_done_count / $total) * 100;
        }
        // update task status if all items has been marked has Terminé
        if ($progress == 100) {
            $task->setStatus("Terminé");
            $trepo->add($task);
        }
        return $this->render('items/index.html.twig', [
            'items' => $items,
            'task' => $task,
            'total' => $total,
            'progress' => $progress,
            'task_id' => $task_id,
        ]);
    }

    #[Route('/new/{task_id}', name: 'app_items_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ItemsRepository $itemsRepository): Response
    {
        // get task id
        $task_id = $request->get('task_id');
        // get task info
        $task = $this->em->find(Tasks::class, $task_id);

        $item = new Items();
        $form = $this->createForm(ItemsType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemsRepository->add($item);
            return $this->redirectToRoute('app_items_index', [
                'task_id' => $task_id,
            ]);
        }

        return $this->renderForm('items/new.html.twig', [
            'item' => $item,
            'form' => $form,
            'task' => $task,
            'task_id' => $task_id,
        ]);
    }

    #[Route('/{id}/show/{task_id}', name: 'app_items_show', methods: ['GET'])]
    public function show(Request $request, Items $item): Response
    {
        // get task id
        $task_id = $request->get('task_id');

        return $this->render('items/show.html.twig', [
            'item' => $item,
            'task_id' => $task_id,
        ]);
    }

    #[Route('/{id}/edit/{task_id}', name: 'app_items_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Items $item, ItemsRepository $itemsRepository): Response
    {
        // get task id
        $task_id = $request->get('task_id');

        $form = $this->createForm(ItemsType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemsRepository->add($item);
            return $this->redirectToRoute('app_items_index', [
                'task_id' => $task_id,
            ]);
        }

        return $this->renderForm('items/edit.html.twig', [
            'item' => $item,
            'form' => $form,
            'task_id' => $task_id,
        ]);
    }

    #[Route('/{id}', name: 'app_items_delete', methods: ['POST'])]
    public function delete(Request $request, Items $item, ItemsRepository $itemsRepository): Response
    {
        // get task id
        $task_id = $request->get('task_id');
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {

            try {
                $itemsRepository->remove($item);
                // show error message
                $this->addFlash('success', 'Sous Tache supprimé avec succès');
            } catch (\Throwable $th) {
                // show error message
                $this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
            }
        }

        return $this->redirectToRoute('app_items_index', [
            'task_id' => $task_id,
        ]);
    }
}
