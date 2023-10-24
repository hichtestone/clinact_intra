<?php

namespace App\Controller\Admin;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Entity\PresedentWord;
use App\Form\PresedentWordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin/presedentword")
 */
class PresedentWordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/", name="admin.presedentword.index")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function index(): Response
    {
        $words= $this->getDoctrine()->getRepository(PresedentWord::class)->findAll();
        return $this->render('admin/presedent_word/index.html.twig', [
            'words' =>$words,
        ]);
    }
    /**
     * @Route("/new", name="admin.presedentword.new")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function new(Request $request):Response
    {
        $word= new PresedentWord();
        $form     = $this->createForm(PresedentWordType::class, $word);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $word->setUser($this->getUser());
            $word->setCreatedAt(new \DateTime('now'));
            $this->entityManager->persist($word);
            $this->entityManager->flush();

            return $this->redirectToRoute("admin.presedentword.index");
        }

       return $this->render("admin/presedent_word/create.html.twig",[
           'edit'   =>false,
           'form'   =>$form->createView(),
           'word'   =>$word,

       ]) ;
    }

    /**
     * @Route ("/{id}/edit", name="admin.presedentword.edit")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function edit(Request $request, PresedentWord $word):Response
    {
        $form= $this->createForm(PresedentWordType::class,$word);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $word->setUser($this->getUser());
            $word->setUpdatedAt(new \DateTime('now'));
            $this->entityManager->persist($word);
            $this->entityManager->flush();

            return $this->redirectToRoute("admin.presedentword.index");
        }

        return $this->render("admin/presedent_word/create.html.twig",[
            'edit'   =>true,
            'form'   =>$form->createView(),
            'word'   =>$word,

        ]) ;
    }

    /**
     * @Route ("/{id}/archive", name="admin.presedentword.archive")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function archive(PresedentWord $word):Response
    {
        $word->setDeletedAt(new \DateTime('now'));
        $this->entityManager->persist($word);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.presedentword.index")    ;
    }

    /**
     * @Route ("/{id}/desarchive", name="admin.presedentword.desarchive")
     */
    public function desarchive(PresedentWord $word):Response
    {
        $word->setDeletedAt(null);
        $this->entityManager->persist($word);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.presedentword.index")    ;
    }


    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="admin.presedentword.filter.index")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =$this->getDoctrine()->getRepository(PresedentWord::class)->findEntitiesByString($requestString);//->findBy(['object' => $requestString]);
       // dd($entities);
        return $this->render('admin/presedent_word/filter.html.twig', [
            'words' => $entities,
        ]);
    }



    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/date", name="ajax.presedentword.search.date")
     * @Method("GET")
     */
    public function searchByDate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(PresedentWord::class)->findEntitiesByDate($requestString);
        return $this->render('admin/presedent_word/filter.html.twig', [
            'words' => $entities,
        ]);
    }


}
