<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Mails;
use App\Entity\Employee;
use App\Entity\Messages;
use App\Repository\UserRepository;
use App\Repository\MailsRepository;
use App\Repository\EmployeeRepository;
use App\Repository\MessagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Mondestin
class CourriersController extends AbstractController
{
    private $em; //define an entity manager variable 
    private $repo_mail; //define a repository variable for mails
    private $repo_msg; //define a repository variable ofr messages

    public function __construct(MailsRepository $repo_mail, MessagesRepository  $repo_msg, EntityManagerInterface $em)
    {

        // check if the user is connected 
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->repo_mail = $repo_mail; //get the repository
        $this->repo_msg = $repo_msg; //get the repository
        $this->em = $em; //set the entity manager

    }
    #[Route('/courriers', name: 'app_courriers')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // get the current user id
        $user_email = $this->getUser()->getEmail();
        // get all user's mails
        $all_mails_rec = $this->repo_mail->getCurentUserMailsRe($user_email);
        $all_mails_send = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_arch = $this->repo_mail->getCurentUserMailsArchives($user_email);
        $all_mails_corbeille = $this->repo_mail->getCurentUserMailsCorbeille($user_email);
        $email_no_rec = count($all_mails_rec);
        $email_no_send = count($all_mails_send);
        $email_no_arch = count($all_mails_arch);
        $email_no_corb = count($all_mails_corbeille);
        // dd($all_mails_corbeille);

        return $this->render('courriers/index.html.twig', [
            'user_mails' =>  $all_mails_rec,
            'email_no_rec' =>  $email_no_rec,
            'email_no_send' =>  $email_no_send,
            'email_no_arch' =>  $email_no_arch,
            'email_no_corb' =>  $email_no_corb,
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/courriers/read/{id}', name: 'app_courriers_read')]
    public function read(Request $request, EmployeeRepository $employeeRepository): Response
    {
        // get the current user id
        $user_email = $this->getUser()->getEmail();

        // get all user's mails
        $all_mails = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_rec = $this->repo_mail->getCurentUserMailsRe($user_email);
        $all_mails_send = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_arch = $this->repo_mail->getCurentUserMailsArchives($user_email);
        $all_mails_corbeille = $this->repo_mail->getCurentUserMailsCorbeille($user_email);
        $email_no_rec = count($all_mails_rec);
        $email_no_send = count($all_mails_send);
        $email_no_arch = count($all_mails_arch);
        $email_no_corb = count($all_mails_corbeille);

        // get the mail content
        $mail_id = $request->get('id');
        $mail = $this->repo_mail->readThisMail($mail_id);
        $the_mail = $this->repo_msg->readThisMailMessage($mail_id);

        // change the is read status of the mail
        $mail->setIsRead(1);
        $this->repo_mail->add($mail);
        return $this->render('courriers/read.html.twig', [
            'employees' => $employeeRepository->findAll(),
            'user_mails' =>  $all_mails,
            'email_no_rec' =>  $email_no_rec,
            'email_no_send' =>  $email_no_send,
            'email_no_arch' =>  $email_no_arch,
            'email_no_corb' =>  $email_no_corb,
            'the_mail' =>  $the_mail,
            'mail' =>  $mail,
        ]);
    }
    #[Route('/courriers/new', name: 'app_courriers_new')]
    public function new(Request $request, EmployeeRepository $employeeRepository): Response
    {
        // get the current user id
        $send_from = $this->getUser()->getEmail();
        $user_email = $this->getUser()->getEmail();
        $all_mails_rec = $this->repo_mail->getCurentUserMailsRe($user_email);
        $all_mails_send = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_arch = $this->repo_mail->getCurentUserMailsArchives($user_email);
        $all_mails_corbeille = $this->repo_mail->getCurentUserMailsCorbeille($user_email);
        $email_no_rec = count($all_mails_rec);
        $email_no_send = count($all_mails_send);
        $email_no_arch = count($all_mails_arch);
        $email_no_corb = count($all_mails_corbeille);
        $msg = new Messages();

        if ($this->isCsrfTokenValid('mail_form', $request->request->get('_token'))) {
            // get submitted data
            $receivers = $request->get('send_to');
            $object = $request->get('mail_object');
            $body = $request->get('mail_body');
            $send_time = new \DateTime(date('d-m-Y H:i:s'));
            $is_read = 0;
            $placeholder = "important";

            // setting the variables accordingly
            for ($a = 0; $a < count($receivers); $a++) {
                $mail = new Mails();
                // set and save the new mail 
                $mail->setMailObject($object);
                $mail->setSendFrom($send_from);
                $mail->setSendTime($send_time);
                $mail->setSendTo($receivers[$a]);
                $mail->setIsRead($is_read);
                $mail->setPlaceholder($placeholder);
                $this->em->persist($mail);
                $this->em->flush();
                // get the last mail id 
                $last_mail_id = $mail->getId();

                // set and save the conversation message
                $msg->setMailId($this->em->find(Mails::class, $last_mail_id));
                $msg->setMailBody($body);
                $this->em->persist($msg);
                $this->em->flush();
            }
        } else {
            // error success message
            $this->addFlash('error', 'You cant do that action');
            // dd('You cant do that action');
        }
        // show success message
        $this->addFlash('success', 'Le Mail a été envoyé avec succès');
        return $this->redirectToRoute('app_courriers');
    }

    #[Route('/courriers/corbeille/{id}', name: 'app_courriers_corbeille')]
    public function corbeille(Request $request, EmployeeRepository $employeeRepository): Response
    {
        // get the mail content
        $mail_id = $request->get('id');
        $mail = $this->repo_mail->readThisMail($mail_id);
        // change the is read placeholder of the mail
        $mail->setPlaceholder("corbeille");
        $this->repo_mail->add($mail);

        // show success message
        $this->addFlash('success', 'Le Mail a été envoyé dans la corbeille avec succès');
        return $this->redirectToRoute('app_courriers');
    }

    #[Route('/courriers/archived/{id}', name: 'app_courriers_archived')]
    public function archived(Request $request, EmployeeRepository $employeeRepository): Response
    {
        // get the mail content
        $mail_id = $request->get('id');
        $mail = $this->repo_mail->readThisMail($mail_id);
        // change the is read placeholder of the mail
        $mail->setPlaceholder("archived");
        $this->repo_mail->add($mail);
        // show success message
        $this->addFlash('success', 'Le Mail a été archivé avec succès');
        return $this->redirectToRoute('app_courriers');
    }

    #[Route('/courriers/envoyés', name: 'app_courriers_sent')]
    public function sent(EmployeeRepository $employeeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // get the current user id
        $user_email = $this->getUser()->getEmail();
        // get all user's mails
        $all_mails_rec = $this->repo_mail->getCurentUserMailsRe($user_email);
        $all_mails_send = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_arch = $this->repo_mail->getCurentUserMailsArchives($user_email);
        $all_mails_corbeille = $this->repo_mail->getCurentUserMailsCorbeille($user_email);
        $email_no_rec = count($all_mails_rec);
        $email_no_send = count($all_mails_send);
        $email_no_arch = count($all_mails_arch);
        $email_no_corb = count($all_mails_corbeille);
        // dd($all_mails_corbeille);

        return $this->render('courriers/sent.html.twig', [
            'user_mails' =>  $all_mails_send,
            'email_no_rec' =>  $email_no_rec,
            'email_no_send' =>  $email_no_send,
            'email_no_arch' =>  $email_no_arch,
            'email_no_corb' =>  $email_no_corb,
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/courriers/archives', name: 'app_courriers_archives')]
    public function archives(EmployeeRepository $employeeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // get the current user id
        $user_email = $this->getUser()->getEmail();
        // get all user's mails
        $all_mails_rec = $this->repo_mail->getCurentUserMailsRe($user_email);
        $all_mails_send = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_arch = $this->repo_mail->getCurentUserMailsArchives($user_email);
        $all_mails_corbeille = $this->repo_mail->getCurentUserMailsCorbeille($user_email);
        $email_no_rec = count($all_mails_rec);
        $email_no_send = count($all_mails_send);
        $email_no_arch = count($all_mails_arch);
        $email_no_corb = count($all_mails_corbeille);
        // dd($all_mails_corbeille);

        return $this->render('courriers/archived.html.twig', [
            'user_mails' =>  $all_mails_arch,
            'email_no_rec' =>  $email_no_rec,
            'email_no_send' =>  $email_no_send,
            'email_no_arch' =>  $email_no_arch,
            'email_no_corb' =>  $email_no_corb,
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/courriers/corbeilles', name: 'app_courriers_corbeille')]
    public function corbeilles(EmployeeRepository $employeeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // get the current user id
        $user_email = $this->getUser()->getEmail();
        // get all user's mails
        $all_mails_rec = $this->repo_mail->getCurentUserMailsRe($user_email);
        $all_mails_send = $this->repo_mail->getCurentUserMailsSe($user_email);
        $all_mails_arch = $this->repo_mail->getCurentUserMailsArchives($user_email);
        $all_mails_corbeille = $this->repo_mail->getCurentUserMailsCorbeille($user_email);
        $email_no_rec = count($all_mails_rec);
        $email_no_send = count($all_mails_send);
        $email_no_arch = count($all_mails_arch);
        $email_no_corb = count($all_mails_corbeille);
        // dd($all_mails_corbeille);

        return $this->render('courriers/corbeille.html.twig', [
            'user_mails' =>  $all_mails_corbeille,
            'email_no_rec' =>  $email_no_rec,
            'email_no_send' =>  $email_no_send,
            'email_no_arch' =>  $email_no_arch,
            'email_no_corb' =>  $email_no_corb,
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('courriers/delete/{id}', name: 'app_courriers_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->request->get('_token'))) {
        // get the mail content
        $id = $request->get('id');
        $mail = $this->em->find(Mails::class, $id);
        dd($mail->getMessages());
        // $the_user = $this->em->find(User::class, $user_id);
        // try {
        //     $user_repo->remove($the_user);
        //     $this->repo->remove($employee);
        //     // show error message
        //     $this->addFlash('success', 'Le Mail a été supprimé avec succès');
        // } catch (\Throwable $th) {
        //     // show error message
        //     $this->addFlash('error', 'Vous ne pouvez pas perfomer cette opération');
        // }
        // }

        return $this->redirectToRoute('app_courriers_corbeille', [], Response::HTTP_SEE_OTHER);
    }
}
