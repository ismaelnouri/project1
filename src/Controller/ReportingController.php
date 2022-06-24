<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profil;
use App\Entity\Statut;
use App\Entity\Classe;
use App\Entity\Annee;
use App\Entity\Semestre;
use App\Entity\Bulletin;
use App\Entity\Matiere;
use App\Entity\Note;
use App\Entity\NoteSemestrielle;
use App\Entity\Utilisateur;
use App\Entity\Niveau;
use App\Entity\AnneeEncours;
use App\Entity\Eleve;
use App\Entity\CahierTexte;
use App\Entity\Jour;
use App\Entity\Heure;
use App\Entity\ClasseSurveillant;
use App\Entity\Inscription;
use App\Entity\MatiereProf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use League\Csv\Writer;
use League\Csv\Reader;
use Spipu\Html2Pdf\Html2Pdf;
use App\Service\T_HTML2PDF;

class ReportingController extends AbstractController
{
    private $session;
    
  public function __construct(SessionInterface $session)
  {
    $this->session = $session;
  }

    /**
     * @Route("/reporting", name="reporting")
     */
    public function reporting(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $annees = $manager->getRepository(Annee::class)->findBy(['statut'=>1]);
        $classes = $manager->getRepository(Classe::class)->findBy(['statut'=>1]);
        $semestres = $manager->getRepository(Semestre::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                $idannee = $request->request->get('annee');
                $idclasse = $request->request->get('classe');
                $idsemestre = $request->request->get('semestre');
                
                if($idannee == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner l'année.");
                    return $this->redirectToRoute('reporting');
                }
                if($idclasse == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner la classe.");
                    return $this->redirectToRoute('reporting');
                }
                if($idsemestre == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner le semestre.");
                    return $this->redirectToRoute('reporting');
                }
                $eleves = $manager->getRepository(Inscription::class)->findBy(['idAnnee'=>$idannee,'idClasse'=>$idclasse,'statut'=>1]);

                $annee = $manager->getRepository(Annee::class)->findOneBy(['idAnnee'=>$idannee,'statut'=>1]);
                $libannee = $annee->getLibelleAnnee();

                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$idclasse,'statut'=>1]);
                $libclasse = $classe->getLibelleClasse();

                $semestre = $manager->getRepository(Semestre::class)->findOneBy(['idSemestre'=>$idsemestre,'statut'=>1]);
                $libsemestre = $semestre->getLibelleSemestre();
                
                
                return $this->render('surveillant/reporting/reporting.html.twig',[
                    'libclasse' => $libclasse,
                    'libsemestre' => $libsemestre,
                    'classes' => $classes,
                    'annees' => $annees,
                    'semestres' => $semestres,
                    'libannee' => $libannee,
                    'eleves' => $eleves,
                    'idannee' => $idannee,
                    'idclasse' => $idclasse,
                    'idsemestre' => $idsemestre,
                ]);
            }
        }
        
        //dd($allocateurs);
        return $this->render('surveillant/reporting/reporting.html.twig',[
            'classes' => $classes,
            'annees' => $annees,
            'semestres' => $semestres,
        ]);
    }

    /**
     * @Route("/reporting/bilanSemestriel125jjh52{annee}{semestre}{classe}55df65", name="bilanSemestriel")
     */
    public function bilanSemestriel(Request $request, EntityManagerInterface $manager, UserInterface $user, $annee,$semestre,$classe)
    {
        $buls = [];
        $eleves = $manager->getRepository(Inscription::class)->findBy(['idAnnee'=>$annee,'idClasse'=>$classe,'statut'=>1]);
        $notes = $manager->getRepository(NoteSemestrielle::class)->findBy(['idEleve'=>$eleves,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        $buls = $manager->getRepository(Bulletin::class)->findBy(['idEleve'=>$eleves,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        //dd($notes);
        $annee = $manager->getRepository(Annee::class)->findOneBy(['idAnnee'=>$annee,'statut'=>1]);
        $semestre = $manager->getRepository(Semestre::class)->findOneBy(['idSemestre'=>$semestre,'statut'=>1]);
        $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$classe,'statut'=>1]);
        $niveau = $classe->getIdNiveau();
        $section = $classe->getIdNiveau()->getIdSection();
        $matieres = $manager->getRepository(Matiere::class)->findBy(['idNiveau'=>$niveau->getIdNiveau(),'statut'=>1]);
        
        $template = $this->renderView('surveillant/reporting/bilanSemestriel.html.twig',[
            'buls' => $buls,
            'notes' => $notes,
            'annee' => $annee,
            'semestre' => $semestre,
            'section' => $section,
            'niveau' => $niveau,
            'classe' => $classe,
            'eleves' => $eleves,
            'matieres' => $matieres,
        ]);                 
                        
        $html2pdf  = new T_HTML2PDF("L","A4","fr");
        $html2pdf->create('L', 'A4', 'fr', true, 'UTF-8', array(10, 15, 10, 15));
        // En ligne sur Cpanel
        $response = new Response($html2pdf->generatePdf($template, "Consultation"));
        $response->headers->set('Content-type', 'application/pdf');
        return $response;
    }

    /**
     * @Route("/reporting/statistique125jjh52{annee}{semestre}{classe}55df65", name="statistique")
     */
    public function statistique(Request $request, EntityManagerInterface $manager, UserInterface $user, $annee,$semestre,$classe)
    {
        $buls = [];
        $eleves = $manager->getRepository(Inscription::class)->findBy(['idAnnee'=>$annee,'idClasse'=>$classe,'statut'=>1]);
        $notes = $manager->getRepository(NoteSemestrielle::class)->findBy(['idEleve'=>$eleves,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        $buls = $manager->getRepository(Bulletin::class)->findBy(['idEleve'=>$eleves,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        //dd($notes);
        $annee = $manager->getRepository(Annee::class)->findOneBy(['idAnnee'=>$annee,'statut'=>1]);
        $semestre = $manager->getRepository(Semestre::class)->findOneBy(['idSemestre'=>$semestre,'statut'=>1]);
        $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$classe,'statut'=>1]);
        $niveau = $classe->getIdNiveau();
        $section = $classe->getIdNiveau()->getIdSection();
        $matieres = $manager->getRepository(Matiere::class)->findBy(['idNiveau'=>$niveau->getIdNiveau(),'statut'=>1]);
        
        $template = $this->renderView('surveillant/reporting/bilanSemestriel.html.twig',[
            'buls' => $buls,
            'notes' => $notes,
            'annee' => $annee,
            'semestre' => $semestre,
            'section' => $section,
            'niveau' => $niveau,
            'classe' => $classe,
            'eleves' => $eleves,
            'matieres' => $matieres,
        ]);                 
                        
        $html2pdf  = new T_HTML2PDF("P","A4","fr");
        $html2pdf->create('P', 'A4', 'fr', true, 'UTF-8', array(10, 15, 10, 15));
        // En ligne sur Cpanel
        $response = new Response($html2pdf->generatePdf($template, "Consultation"));
        $response->headers->set('Content-type', 'application/pdf');
        return $response;
    }
}