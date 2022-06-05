<?php

/**
 * Author: Johan Mickaël
 * Description: This is the class for employee categories
 */

namespace App\Controller;

use App\Entity\EmployeeCategory;
use App\Form\EmployeeCategoryType;
use App\Repository\EmployeeCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employees/categories', name: 'employee_category_')]
class EmployeeCategoryController extends AbstractController
{
	// The default route rendering the list of the categories of employees
	#[Route('/', name: 'index', methods: ['GET'])]
	public function index(EmployeeCategoryRepository $employeeCategoryRepository): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		// Render the list of Employee category
		return $this->render('employee_category/index.html.twig', [
			'employee_categories' => $employeeCategoryRepository->findAll(),
		]);
	}

	// Route to create a new category of employee
	#[Route('/new', name: 'new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		// Initializing the object EmployeeCategory
		$employeeCategory = new EmployeeCategory();

		// Generating a new form to create a new Employee category
		$form = $this->createForm(EmployeeCategoryType::class, $employeeCategory);

		// Handling the request for incoming error
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Saving the new Employee Category if all informations are right
			$entityManager->persist($employeeCategory);
			$entityManager->flush();

			// Redirecting to the list ok employee category
			return $this->redirectToRoute('employee_category_index', [], Response::HTTP_SEE_OTHER);
		}

		// If there was some errors, redirecting to the form and show all errors
		return $this->renderForm('employee_category/new.html.twig', [
			'employee_category' => $employeeCategory,
			'form' => $form,
		]);
	}

	// Route for showing all informations about a specific Employee Category
	#[Route('/{id}', name: 'show', methods: ['GET'])]
	public function show(EmployeeCategory $employeeCategory): Response
	{
		// Rendering the template for showing all details about the employee category
		return $this->render('employee_category/show.html.twig', [
			'employee_category' => $employeeCategory,
		]);
	}

	// Route to edit an existing Employee Category
	#[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
	public function edit(Request $request, EmployeeCategory $employeeCategory, EntityManagerInterface $entityManager): Response
	{
		// Generating the pre-filled form to modify an existing Employee category
		$form = $this->createForm(EmployeeCategoryType::class, $employeeCategory);

		// Handling the request for incoming error
		$form->handleRequest($request);
		$employeeCategory->handleFields();

		if ($form->isSubmitted() && $form->isValid()) {
			// Updating the current Employee Category inside the database
			$entityManager->flush();

			// Redirecting to the list of Employee Category
			return $this->redirectToRoute('employee_category_index', [], Response::HTTP_SEE_OTHER);
		}

		// If there was some errors, redirecting to the form and show all errors
		return $this->renderForm('employee_category/edit.html.twig', [
			'employee_category' => $employeeCategory,
			'form' => $form,
		]);
	}

	// Route to delete the existing Employee Category
	#[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
	public function delete(Request $request, EmployeeCategory $employeeCategory, EntityManagerInterface $entityManager): Response
	{
		// Verify if the request contains a valid @csrf token
		if ($this->isCsrfTokenValid('delete' . $employeeCategory->getId(), $request->request->get('_token'))) {
			// Mondestin
			try {
				// Removing the Employee Category to the database
				$entityManager->remove($employeeCategory);
				$entityManager->flush();
				// show success message
				$this->addFlash('success', 'Categorie supprimé avec succès');
			} catch (\Throwable $th) {
				// show error message
				$this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
			}
		}

		// Redirecting to the list of Employee Category
		return $this->redirectToRoute('employee_category_index', [], Response::HTTP_SEE_OTHER);
	}
}
