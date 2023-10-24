<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Video;
use App\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/video")
 */
class VideoController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/", name="admin.video.index")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function index(): Response
    {
        $videos= $this->getDoctrine()->getRepository(Video::class)->findAll();
        return $this->render('admin/video/index.html.twig', [
            'videos' => $videos,
        ]);
    }

    /**
     * @Route ("/new", name="admin.video.new", methods={"POST", "GET"})
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function new(Request $request):Response
    {
        $video = new Video();
        $user  = $this->getUser();
        $form  = $this->createForm(VideoType::class, $video );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $video->setCreatedAt(new \DateTime('now'));
            $video->setUser($user);
            $this->entityManager->persist($video);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin.video.index');
        }

        return $this->render('admin/video/new.html.twig', [
            'form'  => $form->createView(),
            'video' => $video,
            'edit'  => false
        ]);
    }

    /**
     * @Route ("/{id}/edit", name="admin.video.edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function edit(Request $request, Video $video): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $video->setUpdatedAt(new \DateTime('now'));

            $this->entityManager->persist($video);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin.video.index');
        }

        return $this->render('admin/video/edit.html.twig', [
            'form'  => $form->createView(),
            'video' => $video,
            'edit'  => false
        ]);
    }

    /**
     * @Route ("/{id}/archive", name="admin.video.archive")
     */
    public function archive(Video $video):Response
    {
        $video->setDeletedAt(new \DateTime('now'));
        $this->entityManager->persist($video);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.video.index")    ;
    }

    /**
     * @Route ("/{id}/desarchive", name="admin.video.desarchive")
     */
    public function desarchive(Video $video):Response
    {
        $video->setDeletedAt(null);
        $this->entityManager->persist($video);
        $this->entityManager->flush();

        return $this->redirectToRoute("admin.video.index");
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="ajax.search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Video::class)->findEntitiesByString($requestString);
        return $this->render('admin/video/search.html.twig', [
            'videos' => $entities,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/title", name="ajax.search.title")
     * @Method("GET")
     */
    public function searchByTitle(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Video::class)->findEntitiesByTitle($requestString);
        return $this->render('admin/video/search.html.twig', [
            'videos' => $entities,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/date", name="ajax.search.date")
     * @Method("GET")
     */
    public function searchByDate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Video::class)->findEntitiesByDate($requestString);
        return $this->render('admin/video/search.html.twig', [
            'videos' => $entities,
        ]);
    }




}
