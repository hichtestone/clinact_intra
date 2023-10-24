<?php

namespace App\Controller\Admin;

use App\Entity\TeamMTH;
use App\Form\TeamMthType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Imagick;
use Spatie\PdfToImage\Pdf;


/**
 * @Route ("/admin/team")
 */
class TeamMTHController extends AbstractController
{
    /**
     * @Route("/", name="admin.teamMth.index")
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function index(Request $request): Response
    {
        $teamsMth = $this->getDoctrine()->getRepository(TeamMTH::class)->findAll();
        $team = new TeamMTH();
        $form = $this->createForm(TeamMthType::class, $team);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $team->setUser($this->getUser());
            $team->setCreatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->persist($team);
            $this->getDoctrine()->getManager()->flush();
            $pdfFilePath =  __DIR__ . '/../../../public/upload/team_mth_document/'.$team->getFileName1();
            $outputImagePath = __DIR__. '/../../../public/upload/team_mth_document/img/'.$team->getId().'.png';
            $pdf = new Pdf($pdfFilePath);
            $pdf->setOutputFormat('png');
            $pdf->saveImage($outputImagePath);

            return $this->redirectToRoute('admin.teamMth.index');
        }

        return $this->render('admin/team_mth/index.html.twig', [
            'teams' => $teamsMth,
            'team' => $team,
            'form' => $form->createView(),
            'edit' => false
        ]);
    }

    /**
     * @Route ("/{id}/show", name="admin.teamMth.show", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function show(TeamMTH $teamMTH, Request $request): Response
    {

        return $this->render('admin/team_mth/show.html.twig', [
            'team' => $teamMTH
        ]);
    }

    /**
     * @Route ("/{id}/edit", name="admin.teamMth.edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @Security ("is_granted('ROLE_SUPER_ADMIN')", message="Vous n'êtes pas autoriser à utiliser ce service")
     */
    public function edit(TeamMTH $teamMTH, Request $request):Response
    {
        $form = $this->createForm(TeamMthType::class, $teamMTH);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $teamMTH->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->persist($teamMTH);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.teamMth.show', [
                'id' => $teamMTH->getId()
            ]);
        }
        return $this->render('admin/team_mth/new.html.twig', [
            'team' => $teamMTH,
            'form' => $form->createView(),
            'edit' => true
        ]);
    }

}
