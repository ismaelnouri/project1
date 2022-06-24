<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profil;
use App\Entity\Statut;
use App\Entity\Utilisateur;
use App\Entity\Matiere;
use App\Entity\TypeMatiere;
use App\Entity\MatiereProf;
use App\Entity\AnneeEncours;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        // if ($user && $this->getUser()->getStatutEmp() == 1 )
        // {
        // $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();

        return $this->render('admin/index.html.twig');
       // }else
       //  {
       //   return $this->redirectToRoute('deconnexion');
       //  }
    }

     /**
     * @Route("/ajoututilisateur", name="ajoututilisateur")
     */
    public function ajoututilisateur(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    {
        $profils = $this->getDoctrine()->getRepository(Profil::class)->findBy(['statut'=>1]);
        $matieres = $this->getDoctrine()->getRepository(TypeMatiere::class)->findBy(['statut'=>1]);
        $anneeencours = $this->getDoctrine()->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();
        
        if($request->request->count() > 0)
        {
            $nom=trim($request->request->get('nom'));
            $prenom=trim($request->request->get('prenom'));
            $tel=$request->request->get('tel');
            $login=trim($request->request->get('login'));
            $profil=$request->request->get('profil');
            $id_matiere=$request->request->get('matiere');
            $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class);
            
            $statut = $this->getDoctrine()->getRepository(Statut::class)->findOneBy(['idStatut'=>[1,2]]);
            $verifLoginUtilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['login'=>$login, 'idStatut'=>$statut]);
            $verifTel = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['telUtilisateur'=>$tel, 'idStatut'=>$statut]);
            if(strlen($nom)==0 || strlen($prenom)==0 || strlen($profil)==0 || strlen($login)==0)
            {
                $this->addFlash('ERREUR', "Merci de renseigner tous les champs marqués *");
                return $this->redirectToRoute('ajoututilisateur');
            }
            if($verifLoginUtilisateur != null)
            {
                $this->addFlash('ERREUR', "Ce login existe déja");
                return $this->redirectToRoute('ajoututilisateur');
            }
            if($verifTel != null)
            {
                $this->addFlash('ERREUR', "Ce numéro de téléphone existe déja");
                return $this->redirectToRoute('ajoututilisateur');
            }
            
            $Utilisateur= new Utilisateur();
            $Utilisateur->setNomUtilisateur($nom);
            $Utilisateur->setPrenomUtilisateur($prenom);
            // $Utilisateur->setDateNaissanceEmp(\DateTime::createFromFormat('Y-m-d', $datenaissance));

            $Utilisateur->setTelUtilisateur($tel);
            $profil = $this->getDoctrine()->getRepository(Profil::class)->find($profil);
            $Utilisateur->setIdProfil($profil);
            $Utilisateur->setLogin($login);
            $passwrd = $encoder->encodePassword($Utilisateur, '1234');
            $Utilisateur->setMotPasse($passwrd);
            $Utilisateur->setDateAjout(new \Datetime());
            $statut = $this->getDoctrine()->getRepository(Statut::class)->findOneBy(['idStatut'=>2]);
            $Utilisateur->setIdStatut($statut);
            $Utilisateur->setStatut(1);
            $Utilisateur->setPreConnexion(0);
            $manager->persist($Utilisateur);

            if($id_matiere)
            {
                $Matiereprof = new MatiereProf();
                $Matiereprof->setIdUtilisateur($Utilisateur);
                $Matiereprof->setIdAnnee($annee);
                $matiere = $this->getDoctrine()->getRepository(TypeMatiere::class)->findOneBy(['idTypeMatiere'=>$id_matiere,'statut'=>1]);
                $Matiereprof->setIdTypeMatiere($matiere);
                $Matiereprof->setDateAjout(new \Datetime());
                $Matiereprof->setStatut(1);
                $manager->persist($Matiereprof);
            }
            
            
            $manager->flush();
            $this->addFlash('SUCCESS', "Ajout effectué avec succès");
            return $this->redirectToRoute('listeutilisateur');
            
        }
        return $this->render('admin/utilisateur/ajout.html.twig', [
           'nom' => '',
           'prenom' => '',
           'tel' => '',
           'profil' => '',
           'login' => '',
           'passwrd' => '',
           'profils' => $profils,
           'matieres' => $matieres
       ]);
    }

    /**
     * @Route("/listeutilisateur", name="listeutilisateur")
     */
    public function listeutilisateur()
    {
        // if ($user && $this->getUser()->getStatutEmp() == 1 )
        // {
        $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();

        return $this->render('admin/utilisateur/liste.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
       // }else
       //  {
       //   return $this->redirectToRoute('deconnexion');
       //  }
    }

    // Fonction pour la desactivation d'un utilisateur
    /**
     * @Route("/desactiver/xx{id}xyzw", name="desactiver_utilisateur")
     */
    public function desactiverU(Request $request, EntityManagerInterface $manager, $id)
    {
            $Utilisateur=$this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
            $statut=$this->getDoctrine()->getRepository(Statut::class)->findOneBy(['idStatut'=>2]);

            $Utilisateur->setIdStatut($statut);
            $manager->persist($Utilisateur);
            $manager->flush();
            $this->addFlash('SUCCESS', "Désactivation effectuée avec succès");
            return $this->redirectToRoute('listeutilisateur');
       
    }

    // Fonction pour l'activation d'un utilisateur
    /**
     * @Route("/activer/xx{id}xyzw", name="activer_utilisateur")
     */
    public function activerU(Request $request, EntityManagerInterface $manager, $id)
    {
            $Utilisateur=$this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
            $statut=$this->getDoctrine()->getRepository(Statut::class)->findOneBy(['idStatut'=>1]);

            $Utilisateur->setIdStatut($statut);
            $manager->persist($Utilisateur);
            $manager->flush();
            $this->addFlash('SUCCESS', "Activation effectuée avec succès");
            return $this->redirectToRoute('listeutilisateur');
       
    }

    // Fonction pour la suppression d'un utilisateur
    /**
     * @Route("/supprimer/xx{id}xyzw", name="supprimer_utilisateur")
     */
    public function supprimerU(Request $request, EntityManagerInterface $manager, $id)
    {
            $Utilisateur=$this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
            $statut=$this->getDoctrine()->getRepository(Statut::class)->findOneBy(['idStatut'=>3]);

            $Utilisateur->setIdStatut($statut);
            $manager->persist($Utilisateur);
            $manager->flush();
            $this->addFlash('SUCCESS', "Suppression effectuée avec succès");
            return $this->redirectToRoute('listeutilisateur');
       
    }

    // Fonction pour la reinitialisation du mot de passe
    /**
     * @Route("/reinitialisation/xx{id}xyzw", name="reinitialiser_utilisateur")
     */
    public function reinitialiseU(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder, $id)
    {
            $Utilisateur=$this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
            $passwrd = $encoder->encodePassword($Utilisateur, '1234');
            $Utilisateur->setMotPasse($passwrd);
            $manager->persist($Utilisateur);
            $manager->flush();
            $this->addFlash('SUCCESS', "Reinitialisation effectuée avec succès");
            return $this->redirectToRoute('listeutilisateur');
       
    }

    // /**
    //  * @Route("/test", name="test")
    //  */
    // public function test(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    // {
    //         $Utilisateur=$this->getDoctrine()->getRepository(Utilisateur::class)->find(1);
    //         $passwrd = $encoder->encodePassword($Utilisateur, '1234');
    //         $Utilisateur->setMotPasse($passwrd);
    //         $manager->persist($Utilisateur);
    //         $manager->flush();
       
    // }

    // Fonction pour la modification d'un utilisateur
    /**
     * @Route("/modifier/xx{id}xyzw", name="modifier_utilisateur")
     */
    public function modifierU(Request $request,EntityManagerInterface $manager, $id)
    {
        $profils = $this->getDoctrine()->getRepository(Profil::class)->findAll();
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        
        if($request->request->count() > 0)
        {
            $nom=trim($request->request->get('nom'));
            $prenom=trim($request->request->get('prenom'));
            $tel=$request->request->get('tel');
            $login=trim($request->request->get('login'));
            $profil=$request->request->get('profil');
            
            
            $verifLoginUtilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->verifLoginUtilisateurModif($utilisateur->getIdUtilisateur(),$login);

            if(strlen($nom)==0 || strlen($prenom)==0 || strlen($profil)==0)
            {
                $this->addFlash('ERREUR', "Merci de renseigner tous les champs marqués *");
                return $this->redirectToRoute('modifier_utilisateur',[
                    'id'=>$utilisateur->getIdUtilisateur()
                ]);
            }
            if($verifLoginUtilisateur != null)
            {
                $this->addFlash('ERREUR', "Ce login existe déja");
                return $this->redirectToRoute('modifier_utilisateur',[
                    'id'=>$utilisateur->getIdUtilisateur()
                ]);
            }
            
    
            $utilisateur->setNomUtilisateur($nom);
            $utilisateur->setPrenomUtilisateur($prenom);
            // $Utilisateur->setDateNaissanceEmp(\DateTime::createFromFormat('Y-m-d', $datenaissance));
            
            $utilisateur->setTelUtilisateur($tel);
            $profil = $this->getDoctrine()->getRepository(Profil::class)->find($profil);
            $utilisateur->setIdProfil($profil);
            
            $manager->persist($utilisateur);
            
            $manager->flush();
            $this->addFlash('SUCCESS', "Modification effectuée avec succès");
            return $this->redirectToRoute('modifier_utilisateur',[
                    'id'=>$utilisateur->getIdUtilisateur()
                ]);
            
        }

        return $this->render('admin/utilisateur/modif.html.twig', [
           'nom' => $utilisateur->getNomUtilisateur(),
           'prenom' => $utilisateur->getPrenomUtilisateur(),
           'tel' => $utilisateur->getTelUtilisateur(),
           'idProfil' => $utilisateur->getIdProfil()->getIdProfil(),
           'libelleProfil' => $utilisateur->getIdProfil()->getLibelleProfil(),
           'idUtilisateur' => $utilisateur->getIdUtilisateur(),
           'login' => $utilisateur->getLogin(),
           'profils' => $profils,
       ]);
    }

}
