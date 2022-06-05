<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


// Mondestin
#[Route('/employee')]
class EmployeeController extends AbstractController
{
    private $em; //define an entity manager variable 
    private $repo; //define a repository variable 

    public function __construct(EmployeeRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo; //get the repository
        $this->em = $em; //set the entity manager
    }
    #[Route('/', name: 'app_employee_index', methods: ['GET'])]
    public function index(): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('employee/index.html.twig', [
            'employees' =>  $this->repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $repo_user, MailerInterface $mailer): Response
    {
        $employee = new Employee();
        $user = new User();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('employee_photo')->getData();
            // check in the form has a user avatar image
            if (!empty($file)) {
                // get and generate the new file name
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();
                // copy the file in the folder directory
                $file->move(
                    $this->getParameter('employees_avatar'),
                    $fichier
                );
            } else {
                // set a default avatar for the user
                $fichier = "avatar.png";
            }

            // add the file in the form data to be save
            $employee->setEmployeePhoto($fichier);
            //generate new employee CODE
            $e_code = "EC" . date('Y') . "" . rand(100, 999) . "" . date('m');
            $employee->setEmployeeCode($e_code);

            // generate new password for the user
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $password = implode($pass);

            // add a new user 
            $user->addRole($this->em->find(Role::class, 2));
            $user->setEmail($form->get('employee_email')->getdata());
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            $user->setUsername($form->get('employee_surname')->getData() . " " . $form->get('employee_name')->getData());

            //sending welcome mail to new employee
            $email = (new TemplatedEmail())
                // ->getHeaders()
                //     ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply')
                ->from(new Address('emilienoreply@gmail.com', 'Admin ROOT HR'))
                ->to(new Address($employee->getEmployeeEmail(), $employee->getEmployeeSurname()))
                ->subject('Nouveau Employée')
                ->htmlTemplate('mails_sent/welcome.html.twig')
                ->context([
                    'employee' => $employee,
                    'password' => $password
                ]);
            $mailer->send($email);
            $this->repo->add($employee);
            $repo_user->add($user);

            // show success message
            $this->addFlash('success', 'Employé(e) enregistré avec succès');
            return $this->redirectToRoute('app_employee_index');
        }

        return $this->renderForm('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('employee_photo')->getData();
            // check in the form has a user avatar image
            if (!empty($file)) {
                // get and generate the new file name
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();
                // copy the file in the folder directory
                $file->move(
                    $this->getParameter('employees_avatar'),
                    $fichier
                );
            } else {
                // set a default avatar for the user
                $fichier = "avatar.png";
            }

            // add the file in the form data to be save
            $employee->setEmployeePhoto($fichier);
            $this->repo->add($employee);
            // show success message
            $this->addFlash('success', 'Employé(e) actualisé avec succès');
            return $this->redirectToRoute('app_employee_index');
        }

        return $this->renderForm('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, UserRepository $user_repo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->request->get('_token'))) {
            // get the current user id
            $user_id = $this->getUser()->getId();
            $the_user = $this->em->find(User::class, $user_id);
            try {
                $user_repo->remove($the_user);
                $this->repo->remove($employee);

                // show error message
                $this->addFlash('success', 'Employé(e) supprimé avec succès');
            } catch (\Throwable $th) {
                // show error message
                $this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
            }
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
