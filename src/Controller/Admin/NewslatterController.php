<?php

namespace App\Controller\Admin;

use App\Entity\Newslatter;
use App\Form\NewslatterType;
use App\Repository\NewslatterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spatie\PdfToImage\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/newslatter")
 */
class NewslatterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/", name="admin.newslatter.index")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function index(NewslatterRepository $repository): Response
    {
        $news= $repository->findAll();
        return $this->render('admin/newslatter/index.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/new", name="admin.newslatter.new")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function new(Request $request):Response
    {
        $news= new Newslatter();
        $form= $this->createForm(NewslatterType::class, $news);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $news->setUser($this->getUser());
            $news->setCreatedAt(new \DateTime('now'));
            $this->entityManager->persist($news);
            $this->entityManager->flush();
            $pdfFilePath =  __DIR__ . '/../../../public/images/news/'.$news->getFileName();
            $outputImagePath = __DIR__. '/../../../public/images/news/img/'.$news->getId().'.png';
            $pdf = new Pdf($pdfFilePath);
            $pdf->setOutputFormat('png');
            $pdf->saveImage($outputImagePath);
            return $this->redirectToRoute("admin.newslatter.index");
        }

        return $this->render("admin/newslatter/create.html.twig",[
            'edit'   =>false,
            'form'   =>$form->createView(),

        ]) ;

    }

    /**
     * @Route ("/{id}/edit", name="admin.newslatter.edit")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function edit(Request $request, Newslatter $new):Response
    {
        $form= $this->createForm(NewslatterType::class,$new);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $new->setUser($this->getUser());
            $new->setUpdatedAt(new \DateTime('now'));
            $this->entityManager->persist($new);
            $this->entityManager->flush();

            return $this->redirectToRoute("admin.newslatter.index");
        }

        return $this->render("admin/newslatter/create.html.twig",[
            'edit'   =>true,
            'form'   =>$form->createView(),
            'new'   =>$new,

        ]) ;
    }

    /**
     * @Route ("/{id}/show", name="admin.newslatter.show")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function show(Request $request, Newslatter $new):Response
    {
        return $this->render("admin/newslatter/modalShowPdf.html.twig",[
            'new'   =>$new,

        ]) ;
    }

    /**
     * @Route ("/{id}/archive", name="admin.newslatter.archive")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function archive(Newslatter $newslatter):Response
    {
        $newslatter->setDeletedAt(new \DateTime('now'));
        $this->entityManager->persist($newslatter);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.newslatter.index")    ;
    }

    /**
     * @Route ("/{id}/desarchive", name="admin.newslatter.desarchive")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function desarchive(Newslatter $newslatter):Response
    {
        $newslatter->setDeletedAt(null);
        $this->entityManager->persist($newslatter);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.newslatter.index")    ;
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/title", name="admin.newslatter.ajax.search.title")
     * @Method("GET")
     */
    public function searchByTitle(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Newslatter::class)->findEntitiesByTitle($requestString);
        return $this->render('admin/newslatter/search.html.twig', [
            'news' => $entities,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/date", name="admin.newslatter.ajax.date")
     * @Method("GET")
     */
    public function searchByDate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Newslatter::class)->findEntitiesByDate($requestString);
        return $this->render('admin/newslatter/search.html.twig', [
            'news' => $entities,
        ]);
    }
}
