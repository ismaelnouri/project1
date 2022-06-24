<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Niveau;
use App\Entity\Statut;
use App\Entity\Classe;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ClasseController extends AbstractController
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
     * @Route("/ajoutclasse", name="ajoutclasse")
     */
    public function ajoutclasse(Request $request, EntityManagerInterface $manager)
    {
        $niveaux = $this->getDoctrine()->getRepository(Niveau::class)->findAll();
        
        if($request->request->count() > 0)
        {
            $code=trim($request->request->get('code'));
            $libelle=trim($request->request->get('libelle'));
            $niveau=$request->request->get('niveau');
            $place=$request->request->get('place');
           
            $verifClasse = $this->getDoctrine()->getRepository(Classe::class)->findOneBy(['codeClasse'=>$code, 'libelleClasse'=>$libelle, 'statut'=>1]);

            if(strlen($code)==0 || strlen($libelle)==0 || strlen($niveau)==0 )
            {
                $this->addFlash('ERREUR', "Merci de renseigner tous les champs marqués *");
                return $this->redirectToRoute('ajoutclasse');
            }
            if($verifClasse != null)
            {
                $this->addFlash('ERREUR', "Cette classe existe déja");
                return $this->redirectToRoute('ajoutclasse');
            }
            
            $classe = new Classe();
            $select_niveau = $this->getDoctrine()->getRepository(Niveau::class)->find($niveau);
            $classe->setIdNiveau($select_niveau);
            $classe->setCodeClasse($code);
            $classe->setLibelleClasse($libelle);
            $classe->setNbrePlace($place);
            $classe->setDateAjout(new \DateTime());
            $classe->setStatut(1);
            $manager->persist($classe);
            $manager->flush();

            $this->addFlash('SUCCESS', "Ajout effectué avec succès");
            return $this->redirectToRoute('listeclasse');
            
        }
        return $this->render('admin/classe/ajout.html.twig', [
           'code' => '',
           'libelle' => '',
           'niveaux' => $niveaux,
       ]);
    }

    /**
     * @Route("/listeclasse", name="listeclasse")
     */
    public function listeclasse()
    {
        // if ($user && $this->getUser()->getStatutEmp() == 1 )
        // {
        $classes = $this->getDoctrine()->getRepository(Classe::class)->findAll();

        return $this->render('admin/classe/liste.html.twig', [
            'classes' => $classes,
        ]);
       // }else
       //  {
       //   return $this->redirectToRoute('deconnexion');
       //  }
    }

    // Fonction pour la desactivation d'une classe
    /**
     * @Route("/desactiver/classe{id}zw", name="desactiver_classe")
     */
    public function desactiverClasse(Request $request, EntityManagerInterface $manager, $id)
    {
            $Classe=$this->getDoctrine()->getRepository(Classe::class)->find($id);
           
            $Classe->setStatut(0);
            $manager->persist($Classe);
            $manager->flush();

            $this->addFlash('NOTIFICATION1', "Désactivation effectuée avec succès");
            return $this->redirectToRoute('listeclasse');
       
    }

    // Fonction pour l'activation d'une classe
    /**
     * @Route("/activer/classe{id}zw", name="activer_classe")
     */
    public function activerClasse(Request $request, EntityManagerInterface $manager, $id)
    {
            $Classe=$this->getDoctrine()->getRepository(Classe::class)->find($id);
        
            $Classe->setStatut(1);
            $manager->persist($Classe);
            $manager->flush();

            $this->addFlash('SUCCESS', "Activation effectuée avec succès");
            return $this->redirectToRoute('listeclasse');
       
    }

    // Fonction pour la suppression d'une classe
    /**
     * @Route("/supprimer/classe{id}zw", name="supprimer_classe")
     */
    public function supprimerClasse(Request $request, EntityManagerInterface $manager, $id)
    {
            $Classe=$this->getDoctrine()->getRepository(Classe::class)->find($id);
            
            $Classe->setStatut(2);
            $manager->persist($Classe);
            $manager->flush();
            $this->addFlash('SUCCESS', "Suppression effectuée avec succès");
            return $this->redirectToRoute('listeclasse');
       
    }

    
    // Fonction pour la modification d'une classe
    /**
     * @Route("/modifier/classe{id}zw", name="modifier_classe")
     */
    public function modifierClasse(Request $request,EntityManagerInterface $manager, $id)
    {
        $niveaux = $this->getDoctrine()->getRepository(Niveau::class)->findAll();
        $classe = $this->getDoctrine()->getRepository(Classe::class)->find($id);
        
        if($request->request->count() > 0)
        {
            $code=trim($request->request->get('code'));
            $libelle=trim($request->request->get('libelle'));
            $niveau=$request->request->get('niveau');
            $place=$request->request->get('place');
           
            $verifClasse = $this->getDoctrine()->getRepository(Classe::class)->verifClasse($id,$code);

            if(strlen($code)==0 || strlen($libelle)==0 || strlen($niveau)==0 )
            {
                $this->addFlash('ERREUR', "Merci de renseigner tous les champs marqués *");
                return $this->redirectToRoute('modifier_classe',[
                    'id'=>$classe->getIdClasse()
                ]);
            }
            if($verifClasse != null)
            {
                $this->addFlash('ERREUR', "Cette classe existe déja");
                return $this->redirectToRoute('modifier_classe',[
                    'id'=>$classe->getIdClasse()
                ]);
            }
            
            $select_niveau = $this->getDoctrine()->getRepository(Niveau::class)->find($niveau);
            $classe->setIdNiveau($select_niveau);
            $classe->setCodeClasse($code);
            $classe->setLibelleClasse($libelle);
            $classe->setNbrePlace($place);
            $manager->persist($classe);
            $manager->flush();

            $this->addFlash('SUCCESS', "Modification effectué avec succès");
            return $this->redirectToRoute('modifier_classe',[
                    'id'=>$classe->getIdClasse()
                ]);
        }
        return $this->render('admin/classe/modif.html.twig', [
           'code' => $classe->getCodeClasse(),
           'libelle' => $classe->getLibelleClasse(),
           'place' => $classe->getNbrePlace(),
           'idClasse'=> $classe->getIdClasse(),
           'idNiveau'=> $classe->getIdNiveau()->getIdNiveau(),
           'libelleNiveau' => $classe->getIdNiveau()->getLibelleNiveau(),
           'niveaux' => $niveaux,
       ]);
    }

}
