<?php

namespace App\Controller;

use App\Entity\Pointage;
use App\Entity\Employee;
use App\Repository\TasksRepository;
use App\Repository\EmployeeRepository;
use App\Repository\PointageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmployeeCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Mondestin
class SalairesController extends AbstractController
{
    private $em; //define an entity manager variable 
    private $repo_emp; //define a repository variable for employyes
    private $repo_cat; //define a repository variable for categories
    private $repopoint; //define a repository variable for pointage
    public function __construct(EmployeeRepository $repo_emp, EmployeeCategoryRepository  $repo_cat, PointageRepository  $repopoint, EntityManagerInterface $em)
    {
        $this->repo_emp = $repo_emp; //get the repository
        $this->repo_cat = $repo_cat; //get the repository
        $this->repopoint = $repopoint; //get the repository
        $this->em = $em; //set the entity manager

    }
    #[Route('/salaires', name: 'app_salaires')]
    public function index(TasksRepository $task_repo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        // get all employees
        $employees = $this->repo_emp->findAll();
        $emp = array('.');
        return $this->render('salaires/index.html.twig', [
            'employees' => $employees,
            'emp' => $emp,
        ]);
    }

    #[Route('/salaires/find', name: 'app_salaires_find')]
    public function search(Request $request, TasksRepository $task_repo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // get all employees
        $employees = $this->repo_emp->findAll();
        // get selected employee
        $employee = $request->get('employe');
        $emp = $this->em->find(Employee::class, $employee);
        $base_salary = $emp->getKpa()->getBaseSalary();

        $tarif = $emp->getKpa()->getHourlyRate();
        $validate = "Validé";
        $pointage = $this->repopoint->getPointages($emp->getEmployeeCode(), $validate);


        dd($pointage);

        // $time1 = strtotime('08:00:00');
        // $time2 = strtotime('09:30:00');
        // $difference = round(abs($time2 - $time1) / 3600, 2);
        // dd($difference);
        return $this->render('salaires/index.html.twig', [
            'employees' => $employees,
            'emp' => $emp,
            'tarif' => $tarif,
            'base_salary' => $base_salary,
        ]);
    }
}
