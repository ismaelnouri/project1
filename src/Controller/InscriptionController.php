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
use App\Entity\Utilisateur;
use App\Entity\Redouble;
use App\Entity\Niveau;
use App\Entity\AnneeEncours;
use App\Entity\Eleve;
use App\Entity\Heure;
use App\Entity\Absence;
use App\Entity\Transfert;
use App\Entity\Oriente;
use App\Entity\ClasseSurveillant;
use App\Entity\Inscription;
use App\Entity\Existant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use League\Csv\Writer;
use League\Csv\Reader;

class InscriptionController extends AbstractController
{
    private $session;
    
  public function __construct(SessionInterface $session)
  {
    $this->session = $session;
  }

    /**
     * @Route("/index/transfert", name="listeeleve")
     */
    public function listeeleve(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $eleves = $manager->getRepository(Transfert::class)->findBy(['inscrit'=>0,'statut'=>1]);

        return $this->render('admin/inscription/listeeleve.html.twig',[
            'eleves' => $eleves
        ]);
    }

    /**
     * @Route("/index/oriente", name="listeoriente")
     */
    public function listeoriente(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $eleves = $manager->getRepository(Oriente::class)->findBy(['inscrit'=>0,'statut'=>1]);

        return $this->render('admin/inscription/listeoriente.html.twig',[
            'eleves' => $eleves
        ]);
    }

    /**
     * @Route("/index/existant", name="listeexistant")
     */
    public function listeexistant(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $eleves = $manager->getRepository(Existant::class)->findBy(['inscrit'=>0,'statut'=>1]);

        return $this->render('admin/inscription/listeexistant.html.twig',[
            'eleves' => $eleves
        ]);
    }

    /**
     * @Route("/ajout/inscription", name="ajouteleve")
     */
    public function ajouteleve(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        //dd('ok');
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();

        if($request->request->count() > 0)
        {
            $file = $request->files->get('fichier');
            $radio = $request->request->get('transfert');
            //dd($file,$radio);
            if($file == null)
            {
                $this->addFlash('ERREUR', "Le fichier n'existe pas!");
                return $this->redirectToRoute('ajouteleve');
            }

            $stream = fopen($file,'r');
            $csv = Reader::createFromStream($stream);
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
            
            // compter les nombres de colonnes du fichier CSV
            while(($data = fgetcsv($stream,1000,";")) != false)
            {
                $num = count($data);
                $colonnes = 0;

                for($c=0; $c < $num; $c++)
                {
                    $colonnes++;
                }
            }
            // fin de la fonction

            if(($radio == "1" && $colonnes != 12) || ($radio == "0" && $colonnes != 9) || ($radio == "2" && $colonnes != 9)  )
            {
                $this->addFlash('ERREUR', "Non concordance du fichier!");
                return $this->redirectToRoute('ajouteleve');
            }

            $test = 0;
            if($radio == "1" && $colonnes == 12)
            {
                $test = 1;
            }
            if($radio == "0" && $colonnes == 9)
            {
                $test = 0;
            }
            if($radio == "2" && $colonnes == 9)
            {
                $test = 2;
            }

            //dd($test);
            $matricule = '';
            foreach($csv as $row)
            {
                if($test == 0)
                {
                    if($row['nom'] == null || $row['prenom'] == null || $row['datenais'] == null || $row['lieunais'] == null || $row['niveau'] == null || $row['date_oriente'] == null || $row['nationalite'] == null || $row['sexe'] == null)
                    {
                        $this->addFlash('ERREUR', "Merci de verifier le contenu du fichier excel car un ou plusieurs champs sont vides!");
                        return $this->redirectToRoute('ajouteleve');
                    }
                    $date = explode("/",$row['date_oriente']);
                    $date = $date[2]."-".$date[1]."-".$date[0];
                }
                if($test == 1)
                {
                    if($row['reference'] == null || $row['nom'] == null || $row['prenom'] == null || $row['datenais'] == null || $row['lieunais'] == null || $row['niveau'] == null || $row['date_transfert'] == null || $row['nationalite'] == null || $row['source'] == null || $row['redouble'] == null || $row['sexe'] == null)
                    {
                        $this->addFlash('ERREUR', "Merci de verifier le contenu du fichier excel car un ou plusieurs champs sont vides!");
                        return $this->redirectToRoute('ajouteleve');
                    }
                    $date = explode("/",$row['date_transfert']);
                    $date = $date[2]."-".$date[1]."-".$date[0];
                    //dd($date);
                }
                if($test == 2)
                {
                    if($row['nom'] == null || $row['prenom'] == null || $row['datenais'] == null || $row['lieunais'] == null || $row['niveau'] == null || $row['nationalite'] == null || $row['redouble'] == null || $row['sexe'] == null)
                    {
                        $this->addFlash('ERREUR', "Merci de verifier le contenu du fichier excel car un ou plusieurs champs sont vides!");
                        return $this->redirectToRoute('ajouteleve');
                    }
                    //dd($date);
                }

                $datenais = explode("/",$row['datenais']);
                $datenais = $datenais[2]."-".$datenais[1]."-".$datenais[0];
                //dd($date);
                $num = $manager->getRepository(Eleve::class)->findOneBy(['statut'=>1],['idEleve'=>'DESC']);
                //dd($num->getMatriculeEleve());
                if($num)
                {
                    $matricule = $num->getMatriculeEleve()+1;
                    // dump($matricule);
                }else
                {
                    $matricule = 1;
                }
                
               
                //dump($matricule);
                

                $verif = $manager->getRepository(Eleve::class)->findOneBy(['matriculeEleve'=>$matricule,'statut'=>1]);

                if($verif != null)
                {
                    $this->addFlash('ERREUR', "Cet eleve matriculé sous ".$matricule." existe déjà!");
                    return $this->redirectToRoute('ajouteleve');
                }

                $eleve = new Eleve();
                $eleve->setMatriculeEleve($matricule);
                $eleve->setNomEleve($row['nom']);
                $eleve->setPrenomEleve($row['prenom']);
                $eleve->setDatenaisEleve(new \DateTime($datenais));
                $eleve->setLieunaisEleve($row['lieunais']);
                $eleve->setTelEleve($row['tel']);
                $eleve->setNationaliteEleve($row['nationalite']);
                $eleve->setSexe($row['sexe']);
                $eleve->setDateAjout(new \DateTime());
                $eleve->setStatut(1);
                $manager->persist($eleve);
                //dump($row['niveau']);

                $niveau = $manager->getRepository(Niveau::class)->findOneBy(['codeNiveau'=>$row['niveau'],'statut'=>1]);
                //dump($niveau);

                //dd($radio,$row['reference'],$row['source'],$row['redouble']);
                if($radio == "1")
                {
                    $transfert = new Transfert();
                    $transfert->setIdEleve($eleve);
                    $transfert->setIdAnnee($annee);
                    $transfert->setIdNiveau($niveau);
                    $transfert->setIdUtilisateur($user);
                    $transfert->setReferenceTransfert($row['reference']);
                    $transfert->setSource($row['source']);
                    $transfert->setRedouble($row['redouble']);
                    $transfert->setTypeTransfert(1);
                    $transfert->setInscrit(0);
                    $transfert->setDateTransfert(new \DateTime($date));                
                    $transfert->setDateAjout(new \DateTime());
                    $transfert->setStatut(1);
                    $manager->persist($transfert);
                    // dump($transfert);
                }
                if($radio == "0")
                {
                    $oriente = new Oriente();
                    $oriente->setIdEleve($eleve);
                    $oriente->setIdAnnee($annee);
                    $oriente->setIdNiveau($niveau);
                    $oriente->setIdUtilisateur($user);
                    $oriente->setInscrit(0);
                    $oriente->setDateOriente(new \DateTime($date));      
                    $oriente->setDateAjout(new \DateTime());
                    $oriente->setStatut(1);
                    $manager->persist($oriente);
                    //dump($oriente);
                }
                if($radio == "2")
                {
                    $existant = new Existant();
                    $existant->setIdEleve($eleve);
                    $existant->setIdAnnee($annee);
                    $existant->setIdNiveau($niveau);
                    $existant->setIdUtilisateur($user);
                    $existant->setInscrit(0);
                    $existant->setRedouble($row['redouble']);      
                    $existant->setDateAjout(new \DateTime());
                    $existant->setStatut(1);
                    $manager->persist($existant);
                    //dump($oriente);
                }
            }
            //die();
            $manager->flush();
            if($radio == "1")
            {
                $this->addFlash('SUCCESS', "La liste des transférés à bien étè ajoutée avec succes.");
            }
            if($radio == "0")
            {
                $this->addFlash('SUCCESS', "La liste des orientés à bien étè ajoutée avec succes.");
            }
            if($radio == "2")
            {
                $this->addFlash('SUCCESS', "La liste des existants à bien étè ajoutée avec succes.");
            }
            return $this->redirectToRoute('listeeleve');
        }
        return $this->render('admin/inscription/ajout.html.twig');
    }

    /**
     * @Route("/transfert/sup{verif}", name="sup_multiple")
     */
    public function sup(Request $request, EntityManagerInterface $manager, $verif = null)
    {
        if($request->request->count() > 0)
        {
            $ids = $request->request->get('mesIds');
            if($ids != null)
            {
                foreach($ids as $id)
                {
                    if($verif == 1)
                    {
                        $transfert = $manager->getRepository(Transfert::class)->findOneBy(['idTransfert'=>$id,'statut'=>1]);
                        $transfert->setStatut(0);
                        $manager->persist($transfert);
                        
                    }else
                    {
                        $oriente = $manager->getRepository(Oriente::class)->findOneBy(['idOriente'=>$id,'statut'=>1]);
                        $oriente->setStatut(0);
                        $manager->persist($oriente);

                    }
                }
                $manager->flush();

                $this->addFlash('SUCCESS', "La suppression à bien étè éffectuée avec succes.");
                return $this->redirectToRoute('listeeleve');
            }else
            {
                $this->addFlash('ERREUR', "Merci de cocher au moins une case.");
                return $this->redirectToRoute('listeeleve');
            }
            
        }
       
    }

    /**
     * @Route("/inscription/ajout", name="ajoutinscription")
     */
    public function ajoutinscription(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        //dd('ok');
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $resultats="";
        $result="";
        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                $id = $request->request->get('classe');
                $source = $request->request->get('source');

                if($id == '' || $source == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner tous les champs.");
                    return $this->redirectToRoute('ajoutinscription');
                }

                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$id,'statut'=>1]);
                $niveau = $classe->getIdNiveau();
               // dd($niveau);
                $place_occupee = $manager->getRepository(Inscription::class)->count(['idClasse'=>$id,'statut'=>1]);
                if($source == 1)
                {
                    $resultats = $manager->getRepository(Transfert::class)->findBy(['idNiveau'=>$niveau,'idAnnee'=>$annee,'inscrit'=>0,'statut'=>1,'typeTransfert'=>1]);
                }
                if($source == 2)
                {
                    $resultats = $manager->getRepository(Oriente::class)->findBy(['idNiveau'=>$niveau,'idAnnee'=>$annee,'inscrit'=>0,'statut'=>1]);
                }
                if($source == 3)
                {
                    $resultats = $manager->getRepository(Existant::class)->findBy(['idNiveau'=>$niveau,'idAnnee'=>$annee,'inscrit'=>0,'statut'=>1]);
                }
                
                $this->session->set('source',$source);
                $this->session->set('classe',$id);
                $this->session->set('place_occupe',$place_occupee);
                //dd($classe,$place_occupee);
                return $this->render('surveillant/inscription/ajout.html.twig',[
                    'classes' => $classes,
                    'classe' => $classe,
                    'occupe' => $place_occupee,
                    'source' => $source,
                    'resultats' => $resultats,
                ]);
            }

            if($request->request->get('valider') == 'valider')
            {
                $ids = $request->request->get('mesIds');
                if($ids != null)
                {
                    foreach($ids as $id)
                    {
                        if($this->session->get('source') == 1)
                        {
                            $result = $manager->getRepository(Transfert::class)->find($id);
                            $redouble = $result->getRedouble();
                            $niveau_redouble = $manager->getRepository(Niveau::class)->findOneBy(['codeNiveau'=>$redouble,'statut'=>1]);
                            if($niveau_redouble)
                            {
                                $new_redouble = new Redouble();
                                $new_redouble->setIdEleve($result->getIdEleve());
                                $new_redouble->setIdNiveau($niveau_redouble);
                                $new_redouble->setDateAjout(new \DateTime());
                                $new_redouble->setStatut(1);
                                $manager->persist($new_redouble);
                            }
                        }
                        if($this->session->get('source') == 2)
                        {
                            $result = $manager->getRepository(Oriente::class)->find($id);
                        }
                        if($this->session->get('source') == 3)
                        {
                            $result = $manager->getRepository(Existant::class)->find($id);
                        }
                        $eleve = $result->getIdEleve();

                        $verifInscription = $manager->getRepository(Inscription::class)->findOneBy(['idEleve'=>$eleve,'idAnnee'=>$annee,'statut'=>1]);
                        //dd($verifInscription);
                        if($verifInscription != null)
                        {
                            $this->addFlash('ERREUR', "Cet élève " .$eleve->getNomEleve(). " " .$eleve->getPrenomEleve(). " à déjà étè inscrit.");
                            return $this->redirectToRoute('ajoutinscription');
                        }
                            
                        $inscription = new Inscription();
                        $inscription->setIdUtilisateur($user);
                        $inscription->setIdEleve($eleve);
                        $classe_inscrit = $manager->getRepository(Classe::class)->find($this->session->get('classe'));
                        $inscription->setIdClasse($classe_inscrit);
                        $inscription->setIdAnnee($annee);
                        $inscription->setCoges(0);
                        $inscription->setDateAjout(new \Datetime());
                        $inscription->setstatut(1);
                        $manager->persist($inscription);
                        //dump($inscription);
                        $result->setInscrit(1);
                    }
                    
                    $manager->persist($result);
                    // dump($result);
                    // die();
                    $manager->flush();
                    $this->addFlash('SUCCESS', "Inscription éffectuée avec succes.");
                    return $this->redirectToRoute('listeinscription');
                }else
                {
                    $this->addFlash('ERREUR', "Merci de cocher au moins une case.");
                    return $this->redirectToRoute('ajoutinscription');
                }
            }
        }
        return $this->render('surveillant/inscription/ajout.html.twig',[
            'classes' => $classes
        ]);
    }

    /**
     * @Route("/inscription/liste", name="listeinscription")
     */
    public function listeinscription(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user->getIdUtilisateur(),'statut'=>1]);
        $heures = $manager->getRepository(Heure::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            $classe = $request->request->get('classe');
            if($classe == '')
            {
                $this->addFlash('ERREUR', "Merci de selectionner une classe.");
                return $this->redirectToRoute('listeinscription');
            }
            // $tabs = [];
            // foreach($classes as $classe)
            // {
            //     $tabs[] = $classe->getIdClasse()->getIdClasse();
            // }
            $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user->getIdUtilisateur(),'statut'=>1]);
            $inscrits = $manager->getRepository(Inscription::class)->findBy(['idClasse'=>$classe,'statut'=>1]);
            //dd($classes,$tabs,$inscrits);
            return $this->render('surveillant/inscription/liste.html.twig',[
                'inscrits' => $inscrits,
                'classes' => $classes,
                'heures' => $heures
            ]);
        }
        return $this->render('surveillant/inscription/liste.html.twig',[
            'classes' => $classes,
        ]);
        
    }

    /**
     * @Route("/Absence", name="ajoutabsence")
     */
    public function ajoutabsence(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
       
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        if($request->request->count() > 0)
        {
            //dd('ok');
            $id = $request->request->get('id');
            $id_heure = $request->request->get('heure');
            $commentaire = trim($request->request->get('commentaire'));

            if($id_heure == '')
            {
                $this->addFlash('ERREUR', "Merci de choisir une heure.");
                return $this->redirectToRoute('listeinscription');
            }
            
            $eleve = $manager->getRepository(Eleve::class)->findOneBy(['idEleve'=>$id]);
            $heure = $manager->getRepository(Heure::class)->findOneBy(['idHeure'=>$id_heure]);
            // $tabs = [];
            // foreach($classes as $classe)
            // {
            //     $tabs[] = $classe->getIdClasse()->getIdClasse();
            // }
            $absence = new Absence();
            $absence->setIdUtilisateur($user);
            $absence->setIdEleve($eleve);
            $absence->setIdAnnee($annee);
            $absence->setIdSemestre($semestre);
            $absence->setIdHeure($heure);
            $absence->setCommentaire($commentaire);
            $absence->setDateAjout(new \DateTime());
            $absence->setStatut(1);

            $manager->persist($absence);
            $manager->flush();

            $this->addFlash('SUCCESS', "Ajout absence éffectuée avec succes.");
            return $this->redirectToRoute('listeinscription');
            //dd($classes,$tabs,$inscrits);
        }
    }

    /**
     * @Route("/absence/liste", name="listeabsence")
     */
    public function listeabsence(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        $absences = $manager->getRepository(Absence::class)->findBy(['idUtilisateur'=>$user,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        //dd($classes,$tabs,$inscrits);

        return $this->render('prof/absence/liste.html.twig',[
            'absences' => $absences,
        ]);
    }

}