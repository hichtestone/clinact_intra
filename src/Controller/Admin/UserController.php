<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * @Route ("/admin/user")
 */
class UserController extends AbstractController
{
    private $encoder;
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager=$entityManager;
        $this->encoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="admin.user.index")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function index(UserRepository $repository): Response
    {
        $users= $repository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route ("/new", name="admin.user.new")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function new(Request $request,  MailerInterface $mailer):Response
    {
        $user= new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user->setCreatedAt(new \DateTime('now'));

            for($longeur_pass=10,$pass='';strlen($pass)<$longeur_pass;$pass.=chr(!mt_rand(0,2)? mt_rand(48,57):(!mt_rand(0,1)?mt_rand(65,90):mt_rand(97,122))));
            {
                $user->setPassword($this->encoder->encodePassword($user, $pass));
            }

            $email = (new TemplatedEmail());
            $email
                ->from(new Address('mohamed.hichem@gmail.com', 'Admin_intranet_clinact'))
                ->to($user->getEmail())
                ->subject('Compte crée avec succès')
                ->htmlTemplate('admin/mail/email1.html.twig')
                ->context([
                    'password' =>  $pass,
                    'mail'    => $user->getEmail(),
                ]);
            $mailer->send($email);
            $this->addFlash('suces', 'l\'utilisateur crée avec succès, le mot de pass est '.$pass);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute("admin.user.index");
        }
        return $this->render("admin/user/create.html.twig",[
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    /**
     * @Route ("/{id}/edit", name="admin.user.edit", methods={"POST", "GET"})
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function edit(User $user, Request $request):Response
    {
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() /*&& $form->isValid()*/){
            $user->setUpdatedAt(new \DateTime('now'));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute("admin.user.index");
        }

        return $this->render("admin/user/edit.html.twig",[
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/{id}/showProfile", name="admin.user.showProfile")
     */
    public function showProfile(User $user):Response
    {
       // dd("test");
        return $this->render("admin/user/show.html.twig",[
            'user'=>$user
        ]);
    }

    /**
     * @Route ("/{id}/archive", name="admin.user.archive")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function archive(User $user):Response
    {
        $user->setDeletedAt(new \DateTime('now'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.user.index")    ;
    }

    /**
     * @Route ("/{id}/desarchive", name="admin.user.desarchive")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function desarchive(User $user):Response
    {
        $user->setDeletedAt(null);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.user.index")    ;
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/Email", name="admin.user.ajax.search.email")
     * @Method("GET")
     */
    public function searchByEmail(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(User::class)->findUserByEmail($requestString);
        return $this->render('admin/user/search.html.twig', [
            'users' => $entities,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/date", name="admin.user.ajax.date")
     * @Method("GET")
     */
    public function searchByDate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(User::class)->findEntitiesByDate($requestString);
        return $this->render('admin/user/search.html.twig', [
            'users' => $entities,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/responsable", name="admin.user.ajax.responsable")
     * @Method("GET")
     */
    public function searchByResponsable(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(User::class)->findEntitiesByResponsable($requestString);
        return $this->render('admin/user/search.html.twig', [
            'users' => $entities,
        ]);
    }
}
