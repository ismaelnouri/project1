<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Spipu\Html2Pdf\Html2Pdf;
use App\Service\T_HTML2PDF;
use League\Csv\Writer;
use League\Csv\Reader;
use App\Entity\Allocation;
use App\Entity\AnneeEncours;
use App\Entity\Annee;
use App\Entity\Classe;
use App\Entity\Inscription;

//use App\Fonctions\Fonctions;

class ComptableController extends AbstractController
{
    private $session;
    
  public function __construct(SessionInterface $session)
  {
    $this->session = $session;
  }

    /**
     * @Route("/comptable/allocateur/liste", name="listeallocateur")
     */
    public function listeallocateur(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $allocateurs = $manager->getRepository(Allocation::class)->findBy(['statut'=>1]);
        
        //dd($allocateurs);
        return $this->render('admin/allocation/listeAllocation.html.twig',[
            'allocateurs' => $allocateurs
        ]);
    }

      /**
     * @Route("/comptable/allocation/ajout", name="ajoutallocateur")
     */
    public function ajoutallocateur(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        //dd('ok');
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        //$classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $eleves = $manager->getRepository(Inscription::class)->findBy(['idAnnee'=>$annee,'statut'=>1]);
        $result="";
        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'valider')
            {
                $ids = $request->request->get('mesIds');
                if($ids != null)
                {
                    foreach($ids as $id)
                    {
                        $result = $manager->getRepository(Inscription::class)->find($id);
                        
                        $eleve = $result->getIdEleve();

                        $verifAllocateur = $manager->getRepository(Allocation::class)->findOneBy(['idEleve'=>$eleve,'idAnnee'=>$annee,'statut'=>1]);
                        //dd($verifInscription);
                        if($verifAllocateur != null)
                        {
                            $this->addFlash('ERREUR', "Cet élève " .$eleve->getNomEleve(). " " .$eleve->getPrenomEleve(). " à déjà étè inscrit comme allocateur.");
                            return $this->redirectToRoute('ajoutallocateur');
                        }
                            
                        $allocateur = new Allocation();
                        $allocateur->setIdUtilisateur($user);
                        $allocateur->setIdEleve($eleve);
                        
                        $allocateur->setIdAnnee($annee);
                        
                        $allocateur->setDateAjout(new \Datetime());
                        $allocateur->setstatut(1);
                        $manager->persist($allocateur);
                        //dump($inscription);
                    }
                    
                    $manager->flush();
                    $this->addFlash('SUCCESS', "Ajout allocateur éffectuée avec succes.");
                    return $this->redirectToRoute('listeallocateur');
                }else
                {
                    $this->addFlash('ERREUR', "Merci de cocher au moins une case.");
                    return $this->redirectToRoute('ajoutinscription');
                }
            }
        }
        return $this->render('admin/allocation/ajoutAllocation.html.twig',[
            'eleves' => $eleves
        ]);
    }

    /**
     * @Route("/comptable/allocateur/paiement", name="paiementallocateur")
     */
    public function paiementallocateur(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        //$classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $eleves = $manager->getRepository(Allocation::class)->findBy(['idAnnee'=>$annee,'statut'=>1]);
        $result="";
        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                $tranche = $request->request->get('tranche');
                

                if($tranche == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner la tranche.");
                    return $this->redirectToRoute('paiementallocateur');
                }

                
                
                $this->session->set('tranche',$tranche);
                
                return $this->render('comptable/allocation/paiementAllocation.html.twig',[
                    'tranche' => $this->session->get('tranche'),
                    'annee' => $annee,
                    'eleves' => $eleves,
                ]);
            }

            if($request->request->get('valider') == 'valider')
            {
                $ids = $request->request->get('mesIds');
                if($ids != null)
                {
                    foreach($ids as $id)
                    {
                        $result = $manager->getRepository(Allocation::class)->find($id);
                        
                        $eleve = $result->getIdEleve();
                        $verifAllocateur = "";
                        if($this->session->get('tranche') == 1)
                        {
                            $verifAllocateur = $manager->getRepository(Allocation::class)->findOneBy(['idEleve'=>$eleve,'idAnnee'=>$annee,'firstSemestre'=>1,'statut'=>1]);
                        }else
                        {
                            $verifAllocateur = $manager->getRepository(Allocation::class)->findOneBy(['idEleve'=>$eleve,'idAnnee'=>$annee,'secondSemestre'=>1,'statut'=>1]);
                        }
                        
                        //dd($verifInscription);
                        if($verifAllocateur != null)
                        {
                            $this->addFlash('ERREUR', "Cet élève " .$eleve->getNomEleve(). " " .$eleve->getPrenomEleve(). " à déjà perçu son allocation.");
                            return $this->redirectToRoute('paiementallocateur');
                        }
                            
                        if($this->session->get('tranche') == 1)
                        {
                            $result->setFirstSemestre(1);
                        }else
                        {
                            $result->setSecondSemestre(1);
                        }
                        
                        $result->setDatePaiement(new \Datetime());
                        $manager->persist($result);
                        //dump($inscription);
                    }
                    
                    $manager->flush();
                    $this->addFlash('SUCCESS', "Paiement éffectué avec succes.");
                    return $this->redirectToRoute('listeallocateur');
                }else
                {
                    $this->addFlash('ERREUR', "Merci de cocher au moins une case.");
                    return $this->redirectToRoute('paiementallocateur');
                }
            }
        }
        return $this->render('comptable/allocation/paiementAllocation.html.twig');
    } 

    /**
     * @Route("/comptable/coges/liste", name="listecoges")
     */
    public function listecoges(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $annees = $manager->getRepository(Annee::class)->findBy(['statut'=>1]);
        $classes = $manager->getRepository(Classe::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                $idannee = $request->request->get('annee');
                $idclasse = $request->request->get('classe');
                
                if($idannee == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner l'année.");
                    return $this->redirectToRoute('paiementcoges');
                }
                if($idclasse == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner la classe.");
                    return $this->redirectToRoute('paiementcoges');
                }
                $eleves = $manager->getRepository(Inscription::class)->findBy(['idAnnee'=>$idannee,'idClasse'=>$idclasse,'coges'=>1,'statut'=>1]);

                $annee = $manager->getRepository(Annee::class)->findOneBy(['idAnnee'=>$idannee,'statut'=>1]);
                $libannee = $annee->getLibelleAnnee();

                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$idclasse,'statut'=>1]);
                $libclasse = $classe->getLibelleClasse();
                
                
                return $this->render('comptable/coges/listeCoges.html.twig',[
                    'libclasse' => $libclasse,
                    'classes' => $classes,
                    'annees' => $annees,
                    'libannee' => $libannee,
                    'eleves' => $eleves,
                ]);
            }
        }
        
        //dd($allocateurs);
        return $this->render('comptable/coges/listeCoges.html.twig',[
            'classes' => $classes,
            'annees' => $annees,
        ]);
    }

    /**
     * @Route("/comptable/coges/paiement", name="paiementcoges")
     */
    public function paiementcoges(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annees = $manager->getRepository(Annee::class)->findBy(['statut'=>1]);
        $classes = $manager->getRepository(Classe::class)->findBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        //$classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $result="";
        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                $classe = $request->request->get('classe');
                
                if($classe == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner la classe.");
                    return $this->redirectToRoute('paiementcoges');
                }
                $eleves = $manager->getRepository(Inscription::class)->findBy(['idAnnee'=>$annee,'idClasse'=>$classe,'coges'=>0,'statut'=>1]);
                $this->session->set('classe',$classe);
                
                return $this->render('comptable/coges/paiementCoges.html.twig',[
                    'classe' => $this->session->get('classe'),
                    'classes' => $classes,
                    'annees' => $annees,
                    'annee' => $annee,
                    'eleves' => $eleves,
                ]);
            }

            if($request->request->get('valider') == 'valider')
            {
                $ids = $request->request->get('mesIds');
                if($ids != null)
                {
                    foreach($ids as $id)
                    {
                        $result = $manager->getRepository(Inscription::class)->find($id);
                        
                        $eleve = $result->getIdEleve();
                        $verifCoges = $result->getCoges();
                        
                        //dd($verifInscription);
                        if($verifCoges == 1)
                        {
                            $this->addFlash('ERREUR', "Cet élève " .$eleve->getNomEleve(). " " .$eleve->getPrenomEleve(). " à déjà payé le coges.");
                            return $this->redirectToRoute('paiementcoges');
                        }else
                        {
                            $result->setCoges(1);
                            $result->setDatePaiement(new \Datetime());
                            $manager->persist($result);
                            //dump($inscription);
                        }
                    }
                    
                    $manager->flush();
                    $this->addFlash('SUCCESS', "Paiement éffectué avec succes.");
                    return $this->redirectToRoute('listecoges');
                }else
                {
                    $this->addFlash('ERREUR', "Merci de cocher au moins une case.");
                    return $this->redirectToRoute('paiementcoges');
                }
            }
        }
        return $this->render('comptable/coges/paiementCoges.html.twig',[
            'annees'=>$annees,
            'classes'=>$classes
        ]);
    }
}
