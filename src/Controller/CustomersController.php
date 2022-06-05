<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\CustomersType;
use App\Repository\CustomersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customers')]
class CustomersController extends AbstractController
{
    #[Route('/', name: 'app_customers_index', methods: ['GET'])]
    public function index(CustomersRepository $customersRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('customers/index.html.twig', [
            'customers' => $customersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_customers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomersRepository $customersRepository): Response
    {
        $customer = new Customers();
        $form = $this->createForm(CustomersType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //generate new employee CODE
            $c_code = "CT" . date('Y') . "" . rand(100, 999) . "" . date('m');
            $customer->setCustomerCode($c_code);
            $customersRepository->add($customer);
            // show success message
            $this->addFlash('success', 'Le client a été ajouté avec succès');
            return $this->redirectToRoute('app_customers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customers/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customers_show', methods: ['GET'])]
    public function show(Customers $customer): Response
    {
        return $this->render('customers/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customers $customer, CustomersRepository $customersRepository): Response
    {
        $form = $this->createForm(CustomersType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customersRepository->add($customer);
            // show success message
            $this->addFlash('success', 'Le client a été actualisé avec succès');
            return $this->redirectToRoute('app_customers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customers/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customers_delete', methods: ['POST'])]
    public function delete(Request $request, Customers $customer, CustomersRepository $customersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            // Mondestin
            try {
                $customersRepository->remove($customer);
                // show success message
                $this->addFlash('success', 'Le client a été supprimé avec succès');
            } catch (\Throwable $th) {
                // show error message
                $this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
            }
        }
        return $this->redirectToRoute('app_customers_index', [], Response::HTTP_SEE_OTHER);
    }
}
