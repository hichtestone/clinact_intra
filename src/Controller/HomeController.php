<?php

namespace App\Controller;

use App\Entity\DocumentTransverse;
use App\Entity\Newslatter;
use App\Entity\PresedentWord;
use App\Entity\Takeknowledge;
use App\Entity\TeamMTH;
use App\Entity\Training;
use App\Entity\User;
use App\Entity\Video;
use App\Form\TakeKnowlageType;
use App\Form\UploadTrainingType;
use App\Repository\NewslatterRepository;
use App\Repository\PresedentWordRepository;
use App\Repository\TeamMTHRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Spatie\PdfToImage\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {

        $lastWord=$this->getDoctrine()->getManager()->getRepository(PresedentWord::class)->findLastWord();
        $latnewslatter= $this->getDoctrine()->getManager()->getRepository(Newslatter::class)->findLastOne();
        $lastwebinar=$this->getDoctrine()->getManager()->getRepository(Video::class)->findLastOne();
        $webinars=$this->getDoctrine()->getManager()->getRepository(Video::class)->findAll();
        $teams=$this->getDoctrine()->getManager()->getRepository(TeamMTH::class)->findlasts();

        return $this->render('home/index.html.twig', [
            'lastWord' => $lastWord,
            'lastNew'  => $latnewslatter,
            'webinar'  =>$lastwebinar,
            'webinars'=>$webinars,
            'teams'=>$teams,
        ]);
    }

    /**
     * @Route ("/replay", name="replay")
     */
     public function showVideo(VideoRepository $repository, TeamMTHRepository $teamMTHRepository)
    {
       $videos   = $repository->findVideoOrdred();
       $teamsMth = $teamMTHRepository->findTeamOrdred();

        return $this->render('video/index.html.twig',[
            'videos' => $videos,
            'teams'  => $teamsMth
        ]);

    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/date", name="webinaire.ajax.search.date")
     * @Method("GET")
     */
    public function searchByDate(Request $request)
    {
        $route = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');

        if (strpos($route, "newslatter") != false){
            $entities =  $em->getRepository(Newslatter::class)->findEntitiesByDate($requestString);
            return $this->render('newslatter/search.html.twig', [
                'news' => $entities,
            ]);
        }
        else
            $entities =  $em->getRepository(Video::class)->findEntitiesByDate($requestString);
            return $this->render('video/search.html.twig', [
                'videos' => $entities,
            ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/title", name="webinaire.ajax.search.title")
     * @Method("GET")
     */
    public function searchByTitle(Request $request)
    {

        $route = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        if (strpos($route, "replay") != false){
            $entities =  $em->getRepository(Video::class)->findEntitiesByTitle($requestString);
            return $this->render('video/search.html.twig', [
                'videos' => $entities,
            ]);
        }else
            $entities =  $em->getRepository(Newslatter::class)->findEntitiesByTitle($requestString);
            return $this->render('newslatter/search.html.twig', [
                'news' => $entities,
            ]);

    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/date/teamMth", name="teamMth.ajax.search.date")
     * @Method("GET")
     */
    public function searchByDateTeamMth(Request $request)
    {
        $route = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $em->getRepository(TeamMTH::class)->findEntitiesByDate($requestString);
        return $this->render('team_mth/search.html.twig', [
            'teams' => $entities,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search/title/teamMth", name="teamMth.ajax.search.title")
     * @Method("GET")
     */
    public function searchByTitleTeamMth(Request $request)
    {

        $route         = $request->headers->get('referer');
        $em            = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        if (strpos($route, "replay") != false){
            $entities =  $em->getRepository(TeamMTH::class)->findEntitiesByCriteria($requestString);

            return $this->render('team_mth/search.html.twig', [
                'teams' => $entities,
            ]);
        }
        else
            $entities =  $em->getRepository(TeamMTH::class)->findEntitiesByCriteria($requestString);

        return $this->render('admin/team_mth/search.html.twig', [
            'teams' => $entities,
        ]);

    }

    /**
     * @Route ("/newslatter", name="newslatter")
     */
    public function newslatter(NewslatterRepository $repository):Response
    {
        $news= $repository->findAll();

        return $this->render('newslatter/index.html.twig',[
            'news'=>$news,
        ]);
    }


    /**
     * @Route ("/{id}/show_newslatter", name="newslatter.show")
     */
    public function showNewslatter(Newslatter $new):Response
    {
        return $this->render("admin/newslatter/modalShowPdf.html.twig",[
            'new'   =>$new,

        ]) ;
    }

    public  function level2(User $user,$level)
    {
            if($user->getParent()==null){
               return $level;
            }
            else{
                $level=$level+1;
                return $this->level2($user->getParent(),$level);
            }

        return $level;
    }
    /**
     * @Route("/organigramme", name="organigramme")
     */
    public function showOrganigramme()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        $ussergrouped = [];
        $tempo = [];
        $level = 0;
        $l=0;
        $paentstab= [];
        foreach ($users as $user){
            if($user->getParent()!= null)
            $paentstab[]=$user->getParent()->getId();
        }
        $paentstab=array_unique($paentstab);
        while($l< count($users)) {
            foreach ($users as $user) {
                    if ($level == $this->level2($user,0)) {
                        array_push($tempo,$user);
                        $l++;
                    }
            }
            $ussergrouped[$level] = $tempo;
            if(count($tempo)>0){
                $curentpaent=$tempo[0]->getParent();
            }
            $tempo=[];
            $level++;
        }

        return $this->render("equipe/organigramme.html.twig",[
            'users'         =>$users,
            'users1'         =>$ussergrouped,
            'parentsTab'    =>$paentstab,
        ]);
    }

    /**
     * @Route ("/showProfile", name="showProfile")
     */
    public function showProfile():Response
    {
        return $this->render("user/show.html.twig");
    }

    /**
     * @Route ("/settingProfile", name="settingProfile")
     */
    public function settingProfile()
    {
        return $this->render("user/settingProfile.html.twig");
    }

    /**

     * @Route ("/{id}/ShowNewslatter", name="showNewslatter")
     */
    public function showLastNewslatter(Newslatter $newslatter)
    {
        return $this->render("admin/newslatter/modalShowPdf.html.twig",[
            'new'=>$newslatter,
        ]);
    }
     
     /**
     * @Route ("/trombinoscope", name="trombinoscope", methods={"GET"})
     */
    public function displayTrombinoscope(): Response
    {
        $users= $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return $this->render('equipe/trombinoscope.html.twig',[
            'users' => $users,
        ]);

    }

    /**
     * @Route("/AssuranceQualite", name="assuranceQualite", methods={"GET"})
     */
    public function assuranceQualite()
    {
        $generaldocuments= $this->getDoctrine()->getManager()->getRepository(DocumentTransverse::class)->findGenaralDocuments();
        $qualitiesdocuments= $this->getDoctrine()->getManager()->getRepository(DocumentTransverse::class)->findQualiteDocuments();

        return $this->render("document/assuranceQualite.html.twig",[
            'generaldoc' => $generaldocuments,
            'qualitiesdoc' => $qualitiesdocuments,
        ]);
    }

    /**
     * @Route ("/{id}/showDoc", name="showDoc", methods={"GET"})
     */
    public function ShowDocument(DocumentTransverse $documentTransverse)
    {
        return $this->render("document/show.html.twig",[
            "doc" =>$documentTransverse,
        ]);
    }

    /**
     * @Route ("/{id}/showTraining", name="showTraining", methods={"GET"})
     */
    public function ShowTraining(Training $training)
    {
        return $this->render("document/showTraining.html.twig",[
            "doc" =>$training,
        ]);
    }

    /**
     * @Route ("/supportFormation", name="supportFormation", methods={"GET"})
     */
    public function supportFormation()
    {
        $formations= $this->getDoctrine()->getManager()->getRepository(Training::class)->findBy(['name'=>1]);

        return $this->render("document/formation.html.twig",[
            'formations'=>$formations,

        ]);
    }

    /**
     * @Route ("/{id}/ficheConnaissance", name="ficheConnaissance", methods={"GET","POST"})
     */
    public function ficheConnaissance(DocumentTransverse $documentTransverse, Request $request)
    {
       /* $knowledge= new Takeknowledge();
        $form= $this->createForm(TakeKnowlageType::class,$knowledge);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $knowledge->setSignedAt(new \DateTime('now'));
            $knowledge->setUser($this->getUser());
            $knowledge->setDocumentTransverse($documentTransverse);
            $this->entityManager->persist($knowledge);
            $this->entityManager->flush();

            return $this->redirectToRoute("ficheConnaissance",[
                'form'=>$form->createView(),
                'id'=>$documentTransverse->getId(),
            ]);
        }*/

        return $this->render("document/ficheConnaissance.html.twig",[
            'document'=>$documentTransverse,

        ]);
    }

    /**
     * @Route ("/{id}/signiedDocument", name="signiedDocument", methods={"GET","POST"})
     */
    public function signiedDocument(DocumentTransverse $documentTransverse, Request $request,  MailerInterface $mailer)
    {
        //dd($request->get('isSigned'));

        if($request->get('isSigned')=="on"){
            $knowledge= new Takeknowledge();
            $knowledge->setSignedAt(new \DateTime('now'));
            $knowledge->setUser($this->getUser());
            $knowledge->setIsSigneed(true);
            $knowledge->setDocumentTransverse($documentTransverse);
            $email = (new TemplatedEmail());

            $to=[$this->getUser()->getEmail(),"MHAMMAMI@altra-call.com"];
            $email
                ->from(new Address('aq@multihealthgroup.com', 'Admin_intranet_clinact'))
                ->to($this->getUser()->getEmail())
                ->to("MHAMMAMI@altra-call.com")
                ->subject('Compte crée avec succès')
                ->htmlTemplate('admin/mail/email.html.twig')
                ->context([
                    'password' =>  $documentTransverse->getFilename(),
                    'document' => $documentTransverse,
                    'mail'    => $this->getUser()->getEmail(),
                ]);
            $mailer->send($email);
            $this->entityManager->persist($knowledge);
            $this->entityManager->flush();
        }


       return $this->redirectToRoute("ficheConnaissance",[
           'id'=>$documentTransverse->getId(),
       ]);

    }
}
