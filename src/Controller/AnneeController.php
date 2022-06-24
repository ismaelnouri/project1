<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profil;
use App\Entity\Statut;
use App\Entity\Semestre;
use App\Entity\Classe;
use App\Entity\Utilisateur;
use App\Entity\Annee;
use App\Entity\AnneeEncours;
use App\Entity\Inscription;
use App\Entity\Bulletin;
use App\Entity\Redouble;
use App\Entity\Existant;
use App\Entity\Generation;
use App\Entity\ClasseSurveillant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AnneeController extends AbstractController
{
    public function niveau_sup($niveau)
    {
        $niveau_sup = "";
        if($niveau == "6e")
        {
            $niveau_sup = "5e";
        }
        if($niveau == "5e")
        {
            $niveau_sup = "4e";
        }
        if($niveau == "4e")
        {
            $niveau_sup = "3e";
        }
        if($niveau == "2nd A")
        {
            $niveau_sup = "1ere A";
        }
        if($niveau == "1ere A")
        {
            $niveau_sup = "Tle A";
        }
        if($niveau == "2nd C")
        {
            $niveau_sup = "1ere D";
        }
        if($niveau == "1ere D")
        {
            $niveau_sup = "Tle D";
        }
        return $niveau_sup;
    }
    /**
     * @Route("/annee", name="annee")
     */
    public function annee(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        
        $anneeencours = $this->getDoctrine()->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee_select = $anneeencours->getIdAnnee()->getLibelleAnnee();
        $semestre_select = $anneeencours->getIdSemestre()->getLibelleSemestre();
        $liste_semestre = $this->getDoctrine()->getRepository(Semestre::class)->findBy(['statut'=>1]);

        if($request->request->count() > 0)
        {
            $annee = $request->request->get('annee');
            $semestre_id = $request->request->get('semestre');
            $verif = $this->getDoctrine()->getRepository(Annee::class)->findOneBy(['libelleAnnee'=>$annee,'statut'=>1]);
            $annee_new="";
            if($verif == null)
            {
                $annee_new = new Annee();
                $annee_new->setLibelleAnnee($annee);
                $annee_new->setDateAjout(new \DateTime());
                $annee_new->setStatut(1);
                $manager->persist($annee_new);
            }else
            {
                $annee_new = $this->getDoctrine()->getRepository(Annee::class)->findOneBy(['libelleAnnee'=>$annee,'statut'=>1]);
            }

            $semestre = $this->getDoctrine()->getRepository(Semestre::class)->findOneBy(['idSemestre'=>$semestre_id,'statut'=>1]);
            $anneeencours->setIdAnnee($annee_new);
            $anneeencours->setIdSemestre($semestre);
            $manager->persist($anneeencours);
            $manager->flush();

            $this->addFlash('SUCCESS', "L'année à étè mise à jour avec succes.");
            return $this->redirectToRoute('annee');
        }
        return $this->render('admin/annee/index.html.twig',[
            'annee' => $annee_select,
            'semestre' => $semestre_select,
            'liste_semestre' => $liste_semestre
        ]);
       
    }

    /**
     * @Route("/cloture", name="cloturer")
     */
    public function cloturer(Request $request, EntityManagerInterface $manager,UserInterface $user)
    {                                                                                               
        if($request->request->count() > 0)
        {
            $connection = $manager->getConnection();
            $platform = $connection->getDatabasePlatform();
            $connection->executeUpdate($platform->getTruncateTableSQL('existant', true));

            $anneeencours = $this->getDoctrine()->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
            $annee = explode("/",$anneeencours->getIdAnnee()->getLibelleAnnee());
            $annee_suivante = $annee[1]."/".((int)$annee[1]+1);
            //dd($annee,$annee_suivante);

            $annee_new = new Annee();
            $annee_new->setLibelleAnnee($annee_suivante);
            $annee_new->setDateAjout(new \DateTime());
            $annee_new->setStatut(1);
            $manager->persist($annee_new);

            $nbreclasse = $this->getDoctrine()->getRepository(Classe::class)->count(['statut'=>1]);
            $nbregeneration = $this->getDoctrine()->getRepository(Generation::class)->count(['idAnnee'=>$anneeencours->getIdAnnee(),'idSemestre'=>2,'type'=>2,'statut'=>1]);

            //dd($nbreclasse,$nbregeneration);

            
            if($nbreclasse == $nbregeneration)
            {
                $eleves = $this->getDoctrine()->getRepository(Inscription::class)->findBy(['idAnnee'=>$anneeencours->getIdAnnee(),'statut'=>1]);
                $s1 = 0;
                $s2 = 0;
                $redouble = 0;

                foreach($eleves as $eleve)
                {
                    $redouble = $this->getDoctrine()->getRepository(Redouble::class)->count(['idEleve'=>$eleve->getIdEleve(),'statut'=>1]);

                    $niveau = $eleve->getIdClasse()->getIdNiveau();
                    $s1 = $this->getDoctrine()->getRepository(Bulletin::class)->findOneBy(['idEleve'=>$eleve->getIdEleve(),'idAnnee'=>$anneeencours->getIdAnnee(),'idSemestre'=>1,'statut'=>1]);
                    if($s1)
                    {
                        $s1 = $s1->getMoyenne();
                    }
                
                    $s2 = $this->getDoctrine()->getRepository(Bulletin::class)->findOneBy(['idEleve'=>$eleve->getIdEleve(),'idAnnee'=>$anneeencours->getIdAnnee(),'idSemestre'=>2,'statut'=>1]);
                    if($s2)
                    {
                        $s2 = $s2->getMoyenne();
                    }
                    
                    if(($s1+$s2) < 9.50)
                    {
                        if($redouble == 1)
                        {
                            $exclure = new Exclure();
                            $exclure->setIdEleve($eleve->getIdEleve());
                            $exclure->setIdAnnee($anneeencours->getIdAnnee());
                            $exclure->setIdNiveau($niveau);
                            $exclure->setDateAjout(new \DateTime());
                            $exclure->setStatut(1);
                            $manager->persist($exclureexclure);
                        }else
                        {
                            // il redouble la classe
                            $new_redouble = new Redouble();
                            $new_redouble->setIdEleve($eleve->getIdEleve());
                            $new_redouble->setIdAnnee($anneeencours->getIdAnnee());
                            $new_redouble->setIdNiveau($niveau);
                            $new_redouble->setDateAjout(new \DateTime());
                            $new_redouble->setStatut(1);
                            $manager->persist($new_redouble);


                            // insertion dans la table existant
                            $existant = new Existant();
                           // $existant->setIdUtilisateur($user);
                            $existant->setIdEleve($eleve->getIdEleve());
                            $existant->setIdUtilisateur($user);
                            $existant->setIdAnnee($annee_new);
                            $existant->setIdNiveau($niveau);
                            $existant->setDateAjout(new \DateTime());
                            $existant->setInscrit(0);
                            $existant->setStatut(1);
                            $manager->persist($existant);
                        }

                    }else
                    {
                        //il va passer en classe superieure
                        // insertion dans la table existant
                        $niveau_sup = $this->niveau_sup($niveau->getCodeNiveau());
                        $existant = new Existant();
                        $existant->setIdUtilisateur($user);
                        $existant->setIdEleve($eleve->getIdEleve());
                        $existant->setIdAnnee($annee_new);
                        if($niveau_sup)
                        {
                            $niveau_pass = $this->getDoctrine()->getRepository(Niveau::class)->findOneBy(['codeNiveau'=>$niveau_sup,'statut'=>1]);
                            $existant->setIdNiveau($niveau_pass);
                        }
                        $existant->setDateAjout(new \DateTime());
                        $existant->setInscrit(0);
                        $existant->setStatut(1);
                        $manager->persist($existant);
                    }
                }
                //die();
                $manager->flush();
                $this->addFlash('SUCCESS', "L'année à étè cloturée avec succes.");
            }else
            {
                $this->addFlash('ERREUR', "Impossible de cloturer car la génération du bulletin n'est pas éffective.");
                return $this->redirectToRoute('cloturer');
            }
        }
        return $this->render('admin/cloture/cloture.html.twig');
    }
}