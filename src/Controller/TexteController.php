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
use App\Entity\Note;
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

class TexteController extends AbstractController
{
    private $session;
    
  public function __construct(SessionInterface $session)
  {
    $this->session = $session;
  }

    /**
     * @Route("/cahier", name="ajoutcahier")
     */
    public function ajoutcahier(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();
        $jours = $manager->getRepository(Jour::class)->findBy(['statut'=>1]);
        $heures = $manager->getRepository(Heure::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            //dd('ok');
            $id_jour = $request->request->get('jour');
            $id_heure = $request->request->get('heure');
            $contenu = (trim($request->request->get('contenu')));
            //dd($contenu);
            if($id_heure == '' || $id_jour == '' || strlen($contenu)==0 )
            {
                $this->addFlash('ERREUR', "Merci de renseigner tous les champs.");
                return $this->redirectToRoute('ajoutcahier');
            }
            
            $jour = $manager->getRepository(Jour::class)->findOneBy(['idJour'=>$id_jour]);
            $heure = $manager->getRepository(Heure::class)->findOneBy(['idHeure'=>$id_heure]);
            
            $cahier = new CahierTexte();
            $cahier->setIdUtilisateur($user);
            $cahier->setIdAnnee($annee);
            $cahier->setIdSemestre($semestre);
            $cahier->setidJour($jour);
            $cahier->setIdHeure($heure);
            $cahier->setContenu($contenu);
            $cahier->setDateAjout(new \DateTime());
            $cahier->setValidation(0);
            $cahier->setStatut(1);

            $manager->persist($cahier);
            $manager->flush();

            $this->addFlash('SUCCESS', "Remplissage du cahier éffectué avec succes.");
            return $this->redirectToRoute('listecahier');
            //dd($classes,$tabs,$inscrits);
        }
        return $this->render('prof/cahier/ajout.html.twig',[
            'jours' => $jours,
            'heures' => $heures,
        ]);
    }

    /**
     * @Route("/cahier/liste", name="listecahier")
     */
    public function listecahier(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        $cahiers = $manager->getRepository(CahierTexte::class)->findBy(['idUtilisateur'=>$user,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        //dd($classes,$tabs,$inscrits);

        return $this->render('prof/cahier/liste.html.twig',[
            'cahiers' => $cahiers,
        ]);

    }

    /**
     * @Route("/cahier/listeMultiple", name="listecenseur")
     */
    public function listecenseur(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        $cahiers = $manager->getRepository(CahierTexte::class)->findBy(['idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        $Attente = $manager->getRepository(CahierTexte::class)->findBy(['idAnnee'=>$annee,'idSemestre'=>$semestre,'validation'=>0,'statut'=>1]);
        $Valider = $manager->getRepository(CahierTexte::class)->findBy(['idAnnee'=>$annee,'idSemestre'=>$semestre,'validation'=>1,'statut'=>1]);
        //dd($cahiers,$Attente,$Valider);

        return $this->render('censeur/cahier/listemultiple.html.twig',[
            'attente' => $Attente,
            'valider' => $Valider,
            'cahiers' => $cahiers,
        ]);

    }

    /**
     * @Route("/cahier/validation{id}", name="validation")
     */
    public function validation(Request $request, EntityManagerInterface $manager, UserInterface $user, $id)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        $cahier = $manager->getRepository(CahierTexte::class)->findOneBy(['idCahierTexte'=>$id]);
        $cahier->setValidation(1);
        $manager->persist($cahier);
        $manager->flush();

        $this->addFlash('SUCCESS', "Validation du cahier de texte éffectué avec succes.");
            return $this->redirectToRoute('listecenseur');
        //dd($classes,$tabs,$inscrits);
    }
}