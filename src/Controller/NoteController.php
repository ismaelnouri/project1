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
use App\Entity\Bulletin;
use App\Entity\Redouble;
use App\Entity\Absence;
use App\Entity\Utilisateur;
use App\Entity\Niveau;
use App\Entity\AnneeEncours;
use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\TypeMatiere;
use App\Entity\Transfert;
use App\Entity\TypeDevoir;
use App\Entity\TypeCalcul;
use App\Entity\ClasseSurveillant;
use App\Entity\Generation;
use App\Entity\NoteSemestrielle;
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

class NoteController extends AbstractController
{
    private $session;
    
  public function __construct(SessionInterface $session)
  {
    $this->session = $session;
  }

    /**
     * @Route("/listenote", name="listenote")
     */
    public function listenote(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $matieres = $manager->getRepository(MatiereProf::class)->findBy(['idUtilisateur'=>$user,'idAnnee'=>$annee,'statut'=>1]);
        $devoirs = $manager->getRepository(TypeDevoir::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                //$eleves = $manager->getRepository(Transfert::class)->findBy(['inscrit'=>0,'statut'=>1]);
                $classe = $request->request->get('classe');
                $matiere = $request->request->get('matiere');

                if($classe == '' || $matiere == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner tous les champs.");
                    return $this->redirectToRoute('listenote');
                }

                $this->session->set('classe',$classe);
                $this->session->set('matiere',$matiere);

                $idMatiere = $manager->getRepository(Matiere::class)->findOneBy(['idTypeMatiere'=>$matiere,'statut'=>1]);

                $notes = $manager->getRepository(Note::class)->findBy(['idClasse'=>$classe,'idMatiere'=>$idMatiere,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);

                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$this->session->get('classe'),'statut'=>1]);
                $matiere = $manager->getRepository(TypeMatiere::class)->findOneBy(['idTypeMatiere'=>$this->session->get('matiere'),'statut'=>1]);
               // $devoir = $manager->getRepository(TypeDevoir::class)->findOneBy(['idTypeDevoir'=>$this->session->get('devoir'),'statut'=>1]);

                return $this->render('prof/note/liste.html.twig',[
                    'classes'=> $classes,
                    'matieres' => $matieres,
                    'devoirs' => $devoirs,
                    'classe'=> $classe,
                    'matiere' => $matiere,
                   // 'devoir' => $devoir,
                    'notes' => $notes
                ]);
            }
            if($request->request->get('valider') == 'modif')
            {
                $note = $request->request->get('note');
                $id = $request->request->get('id');
                //dd($note,$id);
                if($note > 20 || $note < 0)
                {
                    $this->addFlash('ERREUR',"Merci de saisir une note valide.");
                    return $this->redirectToRoute('ajoutnote');
                }

                $modif_note = $manager->getRepository(Note::class)->findOneBy(['idNote'=>$id,'statut'=>1]);
                $modif_note->setNote($note);

                $manager->persist($modif_note);
                //dd($modif_note,$note);
                $manager->flush();
                $this->addFlash('SUCCESS',"Modification éffectuée avec succes.");
                return $this->redirectToRoute('listenote');
            }
        }

        return $this->render('prof/note/liste.html.twig',[
            'classes'=> $classes,
            'matieres' => $matieres,
            'devoirs' => $devoirs,
        ]);
    }

    /**
     * @Route("/ajax/responsable", name="ajaxresponsable")
     */
    public function ajaxresponsable(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        if ($request->isXmlHttpRequest())
        {  
            $id = $request->request->get('id'); 
            $retour ="";

            $responsable=$this->getDoctrine()->getRepository(ClasseSurveillant::class)->findOneBy(['idUtilisateur'=>$user,'idClasse'=>$id,'responsable'=>1,'statut'=>1]);
            $matieres=$this->getDoctrine()->getRepository(MatiereProf::class)->findBy(['idUtilisateur'=>$user,'idAnnee'=>$annee,'statut'=>1]);
            if($matieres)
            {
                foreach ($matieres as $enreg ) 
                {
                    $id=$enreg->getIdTypeMatiere()->getIdTypeMatiere();
                    $code=$enreg->getIdTypeMatiere()->getCodeTypeMatiere();
                    $libelle=$enreg->getIdTypeMatiere()->getLibelleTypeMatiere();
                    
                    if ($responsable != null) 
                    {
                        $retour .= "<option value='$id'>$libelle</option>
                                <option value='8'>Conduite</option> 
                                ";
                    }else
                    {
                        $retour .= "<option value='$id'>$libelle</option>";
                    }
                }
            }else
            {
                $retour = "REQUETE";
            }

            $response = new Response(json_encode($retour));
            return $response;
        }
    }

    /**
     * @Route("/note/ajout", name="ajoutnote")
     */
    public function ajoutnote(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();

        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $matieres = $manager->getRepository(MatiereProf::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $devoirs = $manager->getRepository(TypeDevoir::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            if($request->request->get('valider') == 'recherche')
            {
                //$eleves = $manager->getRepository(Transfert::class)->findBy(['inscrit'=>0,'statut'=>1]);
                $classe = $request->request->get('classe');
                $matiere = $request->request->get('matiere');
                $devoir = $request->request->get('devoir');

                if($classe == '' || $matiere == '' || $devoir == '')
                {
                    $this->addFlash('ERREUR', "Merci de renseigner tous les champs.");
                    return $this->redirectToRoute('ajoutnote');
                }

                $this->session->set('classe',$classe);
                $this->session->set('matiere',$matiere);
                $this->session->set('devoir',$devoir);

                $inscrits = $manager->getRepository(Inscription::class)->findBy(['idClasse'=>$classe,'idAnnee'=>$annee,'statut'=>1]);

                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$this->session->get('classe'),'statut'=>1]);
                $matiere = $manager->getRepository(TypeMatiere::class)->findOneBy(['idTypeMatiere'=>$this->session->get('matiere'),'statut'=>1]);
                $devoir = $manager->getRepository(TypeDevoir::class)->findOneBy(['idTypeDevoir'=>$this->session->get('devoir'),'statut'=>1]);

                return $this->render('prof/note/ajout.html.twig',[
                    'classes'=> $classes,
                    'matieres' => $matieres,
                    'devoirs' => $devoirs,
                    'classe'=> $classe,
                    'matiere' => $matiere,
                    'devoir' => $devoir,
                    'inscrits' => $inscrits
                ]);
            }

            if($request->request->get('valider') == 'valider')
            {
                $mesIds = $request->request->get('mesIds');
                //dd($mesIds);
                if($mesIds != null)
                {
                    //dd('ok');
                    foreach($mesIds as $ids)
                    {
                        //dd($ids);
                        $eleve_concerner=$this->getDoctrine()->getRepository(Eleve::class)->findOneBy(['idEleve'=>$ids]);
                        $classe_concerner=$this->getDoctrine()->getRepository(Classe::class)->findOneBy(['idClasse'=>$this->session->get('classe')]);
                        $niveau = $classe_concerner->getIdNiveau()->getIdNiveau();
                        $devoir_concerner = $this->getDoctrine()->getRepository(TypeDevoir::class)->findOneBy(['idTypeDevoir'=>$this->session->get('devoir')]);
                        $matiere_concerner = $this->getDoctrine()->getRepository(Matiere::class)->findOneBy(['idNiveau'=>$niveau,'idTypeMatiere'=>$this->session->get('matiere')]);
                        if($request->request->get('note_'.$ids) != "")
                        {
                            $note = $request->request->get('note_'.$ids);
                            //dd($note);
                            if($note > 20 || $note < 0)
                            {
                                $this->addFlash('ERREUR',"Merci de saisir une note valide pour l'élève " .$eleve_concerner->getNomEleve(). " ".$eleve_concerner->getPrenomEleve().".");
                                return $this->redirectToRoute('ajoutnote');
                            }
                            // $verifnote = $this->getDoctrine()->getRepository(Note::class)->findOneBy(['idEleve'=>$eleve_concerner,'idMatiere'=>$matiere_concerner,'idTypeDevoir'=>$devoir_concerner,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);

                            // if($verifnote == null)
                            // {
                            //     $this->addFlash('ERREUR',"Merci de saisir une note valide pour l'élève " .$eleve_concerner->getNomEleve(). " ".$eleve_concerner->getPrenomEleve().".");
                            //     return $this->redirectToRoute('ajoutnote');
                            // }


                            $new_note = new Note();
                            $new_note->setIdEleve($eleve_concerner);
                            $new_note->setIdClasse($classe_concerner);
                            $new_note->setIdAnnee($annee);
                            $new_note->setIdSemestre($semestre);
                            $new_note->setIdTypeDevoir($devoir_concerner);
                            $new_note->setIdMatiere($matiere_concerner);
                            $new_note->setNote($note);
                            $new_note->setDateAjout(new \Datetime());
                            $new_note->setStatut(1);
                            $manager->persist($new_note);
                            //dump($new_note);

                            if($matiere_concerner->getIdMatiere() == 8)
                            {
                                $absence = $manager->getRepository(Absence::class)->count(['idEleve'=>$eleve_concerner->getIdEleve(),'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
                                $nbrAbsence = (int)($absence / 3);
                                $cond = $note - $nbrAbsence;
                                $semestrielle = new NoteSemestrielle();
                                $semestrielle->setIdProf($user);
                                $semestrielle->setIdEleve($eleve_concerner);
                                $semestrielle->setIdAnnee($annee);
                                $semestrielle->setIdSemestre($semestre);
                                $semestrielle->setIdMatiere($matiere_concerner);
                                $semestrielle->setCoefficient($matiere_concerner->getCoefficientMatiere());
                                $semestrielle->setNoteClasse($cond);
                                $semestrielle->setNoteCompo($cond);
                                $semestrielle->setDateAjout(new \DateTime());
                                $semestrielle->setStatut(1);
                                $manager->persist($semestrielle);
                                //dump($semestrielle);
                            }
                            
                        }
                    }
                    //die();
                    $manager->flush();
                    $this->addFlash('SUCCESS', "Ajout note de ".$devoir_concerner->getLibelleTypeDevoir(). " effectué avec succes. ");
                    return $this->redirectToRoute('ajoutnote');

                }else
                {
                    $this->addFlash('ERREUR', "Impossible de valider car aucune donnée n'a étè trouvée.");
                    return $this->redirectToRoute('ajoutnote');
                }
            }
        }
        return $this->render('prof/note/ajout.html.twig',[
            'classes'=> $classes,
            'matieres' => $matieres,
            'devoirs' => $devoirs
        ]);
    }

    /**
     * @Route("/semestrielle", name="semestrielle")
     */
    public function semestrielle(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();
        
        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        $calculs = $manager->getRepository(TypeCalcul::class)->findBy(['statut'=>1]);

        $noteFinal = "";
        $noteClasse = "";

        if($request->request->count() > 0)
        {
            $classe = $request->request->get('classe');
            $calcul = $request->request->get('calcul');
           // dd($classe,$calcul);

            if($classe == '' || $calcul == '')
            {
                $this->addFlash('ERREUR',"Merci de renseigner tous les champs.");
                return $this->redirectToRoute('semestrielle');
            }

            $eleves = $manager->getRepository(Inscription::class)->findBy(['idClasse'=>$classe,'idAnnee'=>$annee,'statut'=>1]);
            $matiere = $manager->getRepository(ClasseSurveillant::class)->findOneBy(['idClasse'=>$classe,'idAnnee'=>$annee,'idUtilisateur'=>$user,'statut'=>1]);

            $verif = $manager->getRepository(Generation::class)->findOneBy(['idClasse'=>$classe,'idAnnee'=>$annee,'idSemestre'=>$semestre,'type'=>1,'statut'=>1]);

            if($verif == null)
            {
                foreach($eleves as $eleve)
                {
                    
                    if($calcul == 1)
                    {
                        $NoteDevoirMax = $manager->getRepository(Note::class)->NoteDevoirMax($eleve->getIdEleve(),$annee->getIdAnnee(),$semestre->getIdSemestre());
                        $NoteDevoirMax = $NoteDevoirMax['max_note'];
                        $noteFinal = $NoteDevoirMax;
                    }
                    if($calcul == 2)
                    {
                        $NoteDevoirMoyenne = $manager->getRepository(Note::class)->NoteDevoirMoyenne($eleve->getIdEleve(),$annee->getIdAnnee(),$semestre->getIdSemestre());
                        $NoteDevoirMoyenne = $NoteDevoirMoyenne['moyenne_note'];
                        $noteFinal = $NoteDevoirMoyenne;
                    }
                    // if($calcul == 3)
                    // {
                    //     $NoteDevoirMin = $manager->getRepository(Note::class)->NoteDevoirMax($eleve->getIdEleve(),$annee->getIdAnnee(),$semestre->getIdSemestre());
                    //     $NoteDevoirMin = $NoteDevoirMin['min_note'];
                    //     $noteFinal = $NoteDevoirMin;
                    // }
                    
                    $noteDevoirSurveille = $manager->getRepository(Note::class)->findOneBy(['idEleve'=>$eleve->getIdEleve(),'idAnnee'=>$annee->getIdAnnee(),'idSemestre'=>$semestre->getIdSemestre(),'idTypeDevoir'=>3,'statut'=>1]);
                    if($noteFinal)
                    {
                        if($noteDevoirSurveille)
                        {
                            $noteClasse = (((double)$noteFinal + (double)$noteDevoirSurveille->getNote())/2);
                        }else
                        {
                            $this->addFlash('ERREUR',"Merci de renseigner les notes du devoir surveillé.");
                            return $this->redirectToRoute('semestrielle');
                        }        
                    }else
                    {
                        $noteClasse = ((double)$noteDevoirSurveille->getNote());
                    }
                    $noteComposition = $manager->getRepository(Note::class)->findOneBy(['idEleve'=>$eleve->getIdEleve(),'idAnnee'=>$annee->getIdAnnee(),'idSemestre'=>$semestre->getIdSemestre(),'idTypeDevoir'=>4,'statut'=>1]);
                    if(!$noteComposition)
                    {
                        $this->addFlash('ERREUR',"Merci de renseigner les notes de la composition.");
                        return $this->redirectToRoute('semestrielle');
                    }

                     //dd($noteFinal,$noteDevoirSurveille,$noteClasse,$noteComposition);
                    $semestrielle = new NoteSemestrielle();
                    $semestrielle->setIdProf($user);
                    $semestrielle->setIdEleve($eleve->getIdEleve());
                    $semestrielle->setIdAnnee($annee);
                    $semestrielle->setIdSemestre($semestre);
                    $semestrielle->setIdMatiere($noteDevoirSurveille->getIdMatiere());
                    $semestrielle->setCoefficient($noteDevoirSurveille->getIdMatiere()->getCoefficientMatiere());
                    $semestrielle->setNoteClasse($noteClasse);
                    $semestrielle->setNoteCompo($noteComposition->getNote());
                    $semestrielle->setDateAjout(new \DateTime());
                    $semestrielle->setStatut(1);
                    $manager->persist($semestrielle);
                    //dump($semestrielle);

                }
                
                
                $generation = new Generation();
                $generation->setIdAnnee($annee);
                $generation->setIdSemestre($semestre);
                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$classe,'statut'=>1]);
                $generation->setIdClasse($classe);
                $generation->setType(1);
                $generation->setDateGeneration(new \DateTime());
                $generation->setStatut(1);
                $manager->persist($generation);
               // dump($generation);

               // die();
                $manager->flush();

                $this->addFlash('SUCCESS',"Génération éffectuée avec succes.");
                return $this->redirectToRoute('semestrielle');
            }else
            {
                $this->addFlash('ERREUR',"Les notes de cette classe ont été déjà générées.");
                return $this->redirectToRoute('semestrielle');
            }
        }
        return $this->render('prof/note/semestrielle.html.twig',[
            'classes' => $classes,
            'calculs' => $calculs
        ]);

    }

    /**
     * @Route("/bulletin", name="bulletin")
     */
    public function bulletin(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();
        $bul = 0;
        $verifbul = $manager->getRepository(Bulletin::class)->findBy(['idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
        if($verifbul)
        {
            $bul = 1;
        }
        //dd($bul);
        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);

        $noteFinal = "";
        $tabMoy = [];

        if($request->request->count() > 0)
        {
            $classe = $request->request->get('classe');
           // dd($classe,$calcul);

            if($classe == '')
            {
                $this->addFlash('ERREUR',"Merci de renseigner tous les champs.");
                return $this->redirectToRoute('bulletin');
            }

            $eleves = $manager->getRepository(Inscription::class)->findBy(['idClasse'=>$classe,'idAnnee'=>$annee,'statut'=>1]);
           // $matiere = $manager->getRepository(MatiereProf::class)->findOneBy(['idAnnee'=>$annee,'idUtilisateur'=>$user,'statut'=>1]);

            $verif = $manager->getRepository(Generation::class)->findOneBy(['idClasse'=>$classe,'idAnnee'=>$annee,'idSemestre'=>$semestre,'type'=>2,'statut'=>1]);

            if($verif == null)
            {
                foreach($eleves as $eleve)
                {   
                    $total = $manager->getRepository(Note::class)->Total($eleve->getIdEleve(),$semestre->getIdSemestre(),$annee->getIdAnnee());
                    $total = round(($total['total']),2);
                    $coefficient = $manager->getRepository(Note::class)->CoefficientParNiveau($eleve->getIdClasse()->getIdNiveau()->getIdNiveau());
                    $coefficient = $coefficient['coefficient'];
                    $moyenne = round(($total/$coefficient),2);
                    //dump($total,$coefficient,$moyenne);
                    $tabMoy[$eleve->getIdEleve()->getIdEleve()] = $moyenne;
                     //dd($noteFinal,$noteDevoirSurveille,$noteClasse,$noteComposition);

                    $bulletin = new Bulletin();
                    $bulletin->setIdUtilisateur($user);
                    $bulletin->setIdEleve($eleve->getIdEleve());
                    $bulletin->setIdAnnee($annee);
                    $bulletin->setIdSemestre($semestre);
                    $bulletin->setTotal($total);
                    $bulletin->setMoyenne($moyenne);
                    //$bulletin->setRang($noteComposition->getNote());
                    $bulletin->setDateAjout(new \DateTime());
                    $bulletin->setStatut(1);
                    $manager->persist($bulletin);
                   // dump($bulletin);
                }
                
                //die();
                $generation = new Generation();
                $generation->setIdAnnee($annee);
                $generation->setIdSemestre($semestre);
                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$classe,'statut'=>1]);
                $generation->setIdClasse($classe);
                $generation->setType(2);
                $generation->setDateGeneration(new \DateTime());
                $generation->setStatut(1);
                $manager->persist($generation);
                // dump($generation);

                // die();
                $manager->flush();

                $this->addFlash('SUCCESS',"Génération du bulletin éffectuée avec succes.");
                return $this->redirectToRoute('bulletin');
            }else
            {
                $this->addFlash('ERREUR',"Les bulletins de cette classe ont été déjà générés.");
                return $this->redirectToRoute('bulletin');
            }
        }
        return $this->render('surveillant/generation/bulletin.html.twig',[
            'classes' => $classes,
            'bul' => $bul
        ]);

    }

    /**
     * @Route("/impbulparclasse", name="impbulparclasse")
     */
    public function impression_recu(Request $request,EntityManagerInterface $manager,UserInterface $user)
    {
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        $semestre = $anneeencours->getIdSemestre();
        $classes = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$user,'statut'=>1]);
        if($request->request->count() > 0)
        {
            
            if($request->request->get('valider') == 'recherche')
            {
                $classe = $request->request->get('classe');

                if($classe == '')
                {
                    $this->addFlash('ERREUR',"Merci de renseigner tous les champs.");
                    return $this->redirectToRoute('impbulparclasse');
                }
                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$classe,'statut'=>1]);
                $verif = $manager->getRepository(Generation::class)->findOneBy(['idClasse'=>$classe,'idAnnee'=>$annee,'idSemestre'=>$semestre,'type'=>2,'statut'=>1]);
                $bul = 0;
                if($verif)
                {
                    $bul = 1;
                }
                return $this->render('surveillant/imprimer/imp_par_classe.html.twig',[
                    'classe' => $classe,
                    'classes' => $classes,
                    'bul' => $bul,
                ]);
            }
            if($request->request->get('valider') == 'bul')
            {
                $id = $request->request->get('id');
               // dd($id);
                $classe = $manager->getRepository(Classe::class)->findOneBy(['idClasse'=>$id,'statut'=>1]);
                $niveau = $classe->getIdNiveau();
                $section = $classe->getIdNiveau()->getIdSection();
                $eleves = $manager->getRepository(Inscription::class)->findBy(['idClasse'=>$id,'idAnnee'=>$annee,'statut'=>1]);
                // $idElev = [];
                // foreach($eleves as $eleve)
                // {
                //     $idElev[] = $eleve->getIdEleve();
                // }
                //dd($idElev);
                $ClasseSurveillant = $manager->getRepository(ClasseSurveillant::class)->findOneBy(['idClasse'=>$id,'idAnnee'=>$annee,'responsable'=>1,'statut'=>1]);
                $profResponsable = '';
                if($ClasseSurveillant)
                {
                    $profResponsable = $ClasseSurveillant->getIdUtilisateur();
                }else
                {
                    $profResponsable = '';
                }
                //dd($profResponsable);
                
                $buls = [];
                $ids = [];
                $total = [];
                $moyenne = [];
                $redouble = [];
                $absence = [];
                //dd($eleves);
                foreach($eleves as $eleve)
                {
                    $ids[] = $eleve->getIdEleve()->getIdEleve();
                    $bulletin = $manager->getRepository(Bulletin::class)->findOneBy(['idEleve'=>$eleve->getIdEleve()->getIdEleve(),'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
                    $notes[] = $manager->getRepository(NoteSemestrielle::class)->findBy(['idEleve'=>$eleve->getIdEleve()->getIdEleve(),'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1],['idEleve'=>'DESC']);
                    
                    $buls[] = $bulletin;
                    $total[$bulletin->getIdEleve()->getIdEleve()] = $bulletin->getTotal();
                    $moyenne[$bulletin->getIdEleve()->getIdEleve()] = $bulletin->getMoyenne();
                    $redouble[$eleve->getIdEleve()->getIdEleve()] = $manager->getRepository(Redouble::class)->findOneBy(['idEleve'=>$eleve->getIdEleve()->getIdEleve(),'idAnnee'=>$annee,'statut'=>1]);
                    $absence[$eleve->getIdEleve()->getIdEleve()] = $manager->getRepository(Absence::class)->count(['idEleve'=>$eleve->getIdEleve()->getIdEleve(),'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1]);
                   // dump($buls,$notes);
                }
                $test = $manager->getRepository(Bulletin::class)->findBy(['idEleve'=>$ids,'idAnnee'=>$annee,'idSemestre'=>$semestre,'statut'=>1],['moyenne'=>'DESC']);
               // dd($test);

                // Gestion des rang avec prise en charge des ex
                $rang=1;
                $rangEx=1;
                $indice_inc_ex = 0;
                $tabRang = [];
                $moyenne_precedent = 0 ;
                foreach($test as $id)
                {
                    
                    if($rang==1)
                    {
                        $tabRang[$id->getIdEleve()->getIdEleve()] = $rang;
                    }else
                    {
                        if($moyenne_precedent == $id->getMoyenne())
                        {
                            $tabRang[$id->getIdEleve()->getIdEleve()] = $rangEx;
                            $indice_inc_ex = $id->getMoyenne();
                            if($id->getMoyenne() == $id->getMoyenne())
                            {
                                $rangEx++;
                            }
                        }
                        
                        if($moyenne_precedent > $id->getMoyenne())
                        {
                            $tabRang[$id->getIdEleve()->getIdEleve()] = $rang;
                        }
                    }
                    $moyenne_precedent=$id->getMoyenne();
                    $rang++;
                }
                //dd($tabRang);
                // Fin Gestion des rang avec prise en charge des ex
                $sommeMoyClasse = $manager->getRepository(Note::class)->SommeMoyenneClasse($ids,$annee->getIdAnnee(),$semestre->getIdSemestre());
                $nbreEleve = $manager->getRepository(Note::class)->NbreEleveParClasse($ids,$annee->getIdAnnee());
                //dump($sommeMoyClasse,$nbreEleve);
                $moyenneClasse = ($sommeMoyClasse/$nbreEleve);
                //dd($moyenne,$total,$redouble,$absence,$tabRang);
                //$moyenneClasse = round(($sommeMoyClasse/$nbreEleve),2);
                //dd($sommeMoyClasse,$nbreEleve,$moyenneClasse);
                //die();
                $template = $this->renderView('bul.html.twig',[
                    'buls' => $buls,
                    'notes' => $notes,
                    'annee' => $annee,
                    'semestre' => $semestre,
                    'section' => $section,
                    'niveau' => $niveau,
                    'classe' => $classe,
                    'profResponsable' => $profResponsable,
                    'total' => $total,
                    'moyenne' => $moyenne,
                    'moyenneClasse' => $moyenneClasse,
                    'nbreEleve' => $nbreEleve,
                    'tabRang' => $tabRang,
                    'absence' => $absence,
                    'redouble' => $redouble,
                ]);                 
                        
                $html2pdf  = new T_HTML2PDF("P","A4","fr");
                $html2pdf->create('P', 'A4', 'fr', true, 'UTF-8', array(10, 15, 10, 15));
                // En ligne sur Cpanel
                $response = new Response($html2pdf->generatePdf($template, "Consultation"));
                $response->headers->set('Content-type', 'application/pdf');
                return $response;
            }   
        }
        return $this->render('surveillant/imprimer/imp_par_classe.html.twig',[
            'classes' => $classes,
        ]);
             
    }
}