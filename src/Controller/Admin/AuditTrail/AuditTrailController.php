<?php

namespace App\Controller\Admin\AuditTrail;

use App\Entity\AuditTrail\NewslatterAuditTrail;
use App\Entity\AuditTrail\PresedentWordAuditTrail;
use App\Entity\AuditTrail\TeamMTHAuditTrail;
use App\Entity\AuditTrail\UserAuditTrail;
use App\Entity\AuditTrail\VideoAuditTrail;
use App\Entity\Video;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/admin/audit_trail")
 * @Security("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
 */
class AuditTrailController extends AbstractController
{

    /**
     * @Route("/users", name="admin.audit.trail.users", methods={"GET"})
     */
    public function userAuditTrail(): Response
    {
        $users = $this->getDoctrine()->getRepository(UserAuditTrail::class)->findAll();

        return $this->render('admin/audit_trail/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/videos", name="admin.audit.trail.video", methods={"GET"})
     */
    public function videoAuditTrail(): Response
    {
        $videos = $this->getDoctrine()->getRepository(VideoAuditTrail::class)->findAll();

        return $this->render('admin/audit_trail/videos.html.twig', [
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/president_word", name="admin.audit.trail.presidentWord", methods={"GET"})
     */
    public function presidentWordAuditTrail(): Response
    {
        $words = $this->getDoctrine()->getRepository(PresedentWordAuditTrail::class)->findAll();

        return $this->render('admin/audit_trail/president_words.html.twig', [
            'words' => $words,
        ]);
    }

    /**
     * @Route("/newsletter", name="admin.audit.trail.newsletter", methods={"GET"})
     */
    public function newsletterAuditTrail(): Response
    {
        $newsletters = $this->getDoctrine()->getRepository(NewslatterAuditTrail::class)->findAll();

        return $this->render('admin/audit_trail/newsletters.html.twig', [
            'newsletters' => $newsletters,
        ]);
    }

    /**
     * @Route("/teamsMth", name="admin.audit.trail.teamsMth", methods={"GET"})
     */
    public function teamsMthAuditTrail(): Response
    {
        $teams = $this->getDoctrine()->getRepository(TeamMTHAuditTrail::class)->findAll();

        return $this->render('admin/audit_trail/teams_mth.html.twig', [
            'teams' => $teams,
        ]);
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("audit_trail/search/date", name="audit.trail.ajax.search.date")
     * @Method("GET")
     */
    public function searchByDate(Request $request)
    {
        $route = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        if (strpos($route, "videos") != false){
            $entities =  $em->getRepository(VideoAuditTrail::class)->findEntitiesByDate($requestString);
            return $this->render('admin/audit_trail/search_videos.html.twig', [
                'videos' => $entities,
            ]);
        }elseif (strpos($route, "users") != false){
            $entities =  $em->getRepository(UserAuditTrail::class)->findEntitiesByDate($requestString);
            return $this->render('admin/audit_trail/search_users.html.twig', [
                'users' => $entities,
            ]);
        }elseif (strpos($route, "newsletter")){
            $entities =  $em->getRepository(NewslatterAuditTrail::class)->findEntitiesByDate($requestString);
            return $this->render('admin/audit_trail/search_newsletters.html.twig', [
                'newsletters' => $entities,
            ]);
        }elseif (strpos($route, "teamsMth")){
            $entities =  $em->getRepository(TeamMTHAuditTrail::class)->findEntitiesByDate($requestString);
            return $this->render('admin/audit_trail/search_teams.html.twig', [
                'teams' => $entities,
            ]);
        }else
            $entities =  $em->getRepository(PresedentWordAuditTrail::class)->findEntitiesByDate($requestString);
            return $this->render('admin/audit_trail/search_president.html.twig', [
                'words' => $entities,
            ]);

    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("audit_trail/search/type", name="audit.trail.ajax.search.type")
     * @Method("GET")
     */
    public function searchByType(Request $request)
    {
        $route = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        if (strpos($route, "videos") != false){
            $entities =  $em->getRepository(VideoAuditTrail::class)->findEntitiesByType($requestString);
            return $this->render('admin/audit_trail/search_videos.html.twig', [
                'videos' => $entities,
            ]);
        }elseif (strpos($route, "users") != false){
            $entities =  $em->getRepository(UserAuditTrail::class)->findEntitiesByType($requestString);
            return $this->render('admin/audit_trail/search_users.html.twig', [
                'users' => $entities,
            ]);
        }elseif (strpos($route, "newsletter")){
            $entities =  $em->getRepository(NewslatterAuditTrail::class)->findEntitiesByType($requestString);
            return $this->render('admin/audit_trail/search_newsletters.html.twig', [
                'newsletters' => $entities,
            ]);
        }elseif (strpos($route, "teamsMth")){
            $entities =  $em->getRepository(TeamMTHAuditTrail::class)->findEntitiesByType($requestString);
            return $this->render('admin/audit_trail/search_teams.html.twig', [
                'teams' => $entities,
            ]);
        }else
            $entities =  $em->getRepository(PresedentWordAuditTrail::class)->findEntitiesByType($requestString);
        return $this->render('admin/audit_trail/search_president.html.twig', [
            'words' => $entities,
        ]);

    }
}
