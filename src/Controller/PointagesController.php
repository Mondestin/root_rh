<?php

namespace App\Controller;

use App\Entity\Pointage;
use App\Form\PointageType;
use App\Repository\EmployeeRepository;
use App\Repository\PointageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Mondestin
class PointagesController extends AbstractController
{
    private $em; //define an entity manager variable 
    private $repo; //define a repository variable 

    public function __construct(PointageRepository $repo, EntityManagerInterface $em)
    {
        // check if the user is connected 
        $this->repo = $repo; //get the repository
        $this->em = $em; //set the entity manager

    }

    #[Route('/pointages', name: 'app_pointages_index')]
    public function index(Request $request, EmployeeRepository $employeeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // get the current user id
        $userId = $this->getUser()->getId();
        $userMail = $this->getUser()->getEmail();
        $eInfo = $employeeRepository->getUserDepts($userMail);

        $dept_name = $eInfo->getDepartment()->getDepartmentName();
        $m_surname = $eInfo->getDepartment()->getManagerId()->getEmployeeSurname();
        $m_name = $eInfo->getDepartment()->getManagerId()->getEmployeeName();
        $manager = $m_surname . " " . $m_name;

        $e_code = $eInfo->getEmployeeCode();
        // get today's time and date
        $pDate = new \DateTime(date('d-m-Y'));
        $endTime = new \DateTime(date('H:i:s'));
        // get all the pointages for the current user
        $userChecks = $this->repo->findAllUserChecks($e_code);

        $validate = "Validé";
        $pointages = $this->repo->getPointages($e_code, $validate);
        dd($pointages);

        $pointage = new Pointage();
        $form = $this->createForm(PointageType::class, $pointage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $check = $form->get('status')->getData();
            // check if the user has already checked in today
            $todayExist =  $this->repo->checkTodayIn($e_code, $pDate);
            // dd($check);
            if ($check == "CheckedIn") {
                if (count($todayExist) > 0) {
                    // show warning message
                    $this->addFlash('error', 'Vous avez déjà checked In, veuillez Check Out');
                    // redirect to the user pointage page
                    return $this->redirectToRoute('app_pointages_index');
                } else {
                    // setting the values
                    $pointage->setEmployeeId($e_code);
                    $pointage->setComments('');
                    $pointage->setPointingDate(new \DateTime(date('d-m-Y')));
                    $pointage->setStartTime(new \DateTime(date('H:i:s')));
                    $pointage->setJustify('');
                    $pointage->setValidate('en traitement');
                    $this->em->persist($pointage);
                    $this->em->flush();
                    // show success message
                    $this->addFlash('success', 'Vous avez bien pointé votre début de journée');
                    // redirect to the user pointage page
                    return $this->redirectToRoute('app_pointages_index');
                }
            }
            // update existing row if the user is checking out
            else if ($check == "CheckedOut") {
                $checkOutExist = "";
                foreach ($todayExist as $in) {
                    $checkOutExist = $in->getStatus();
                }
                //check if the user has already check in
                if (empty($todayExist)) {
                    // show warning message if the user is trying to check out without checking in
                    $this->addFlash('error', 'Vous devez Check In pour prétendre Check Out');
                    // redirect to the user pointage page
                    return $this->redirectToRoute('app_pointages_index');
                } else {
                    // dd($checkOutExist);
                    // check if the user has already checked out for today
                    if ($checkOutExist == "CheckedOut") {
                        // show warning message
                        $this->addFlash('error', 'Vous avez déjà checked In et Check Out pour aujourdhui');
                        // redirect to the user pointage page
                        return $this->redirectToRoute('app_pointages_index');
                    } else {

                        // update the check out time
                        $this->repo->updateUserPointage($e_code, $pDate, $endTime, $check);
                        $this->em->flush();
                        // show success message
                        $this->addFlash('success', 'Vous avez bien pointé votre fin de journée');
                        // redirect to the user pointage page
                        return $this->redirectToRoute('app_pointages_index');
                    }
                }
            }
            // show error message
            else {
                // show error message
                $this->addFlash('error', 'Votre pointage a échoué');
                // redirect to the user pointage page
                return $this->redirectToRoute('app_pointages_index');
            }
        }
        return $this->renderForm('pointages/index.html.twig', [
            'form' => $form,
            'manager' => $manager,
            'dept_name' => $dept_name,
            'checks' => $userChecks,
            'employees' => $employeeRepository->findAll(),
        ]);
    }
}
