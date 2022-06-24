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
use App\Entity\ClasseSurveillant;
use App\Entity\AnneeEncours;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AttributionController extends AbstractController
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
     * @Route("/index/attr", name="attr_surveillant_classe")
     */
    public function attr_surveillant_classe(Request $request, EntityManagerInterface $manager, UserInterface $user)
    {
        $surveillants = "";
        if($user->getIdProfil()->getIdProfil()==1)
        {
            $surveillants = $manager->getRepository(Utilisateur::class)->findBy(['idProfil'=>[2,3],'idStatut'=>1]);
        }
        if ($user->getIdProfil()->getIdProfil()==3) 
        {
            $surveillants = $manager->getRepository(Utilisateur::class)->findBy(['idProfil'=>4,'idStatut'=>1]);
        }
        
        $classes = $manager->getRepository(Classe::class)->findBy(['statut'=>1]);
        $anneeencours = $manager->getRepository(AnneeEncours::class)->findOneBy(['statut'=>1]);
        $annee = $anneeencours->getIdAnnee();

        if($request->request->count() > 0)
        {
            $cocher = $request->request->get('classes');
            $id_surveillant = $request->request->get('surveillant');

            if($cocher == null)
            {
                $this->addFlash('ERREUR', "Merci de cocher au moins une classe");
                return $this->redirectToRoute('attr_surveillant_classe');
            }

            $surveillant = $manager->getRepository(Utilisateur::class)->find($id_surveillant);
            foreach($cocher as $id)
            {
                $classe = $manager->getRepository(Classe::class)->find($id);
                $verif = $manager->getRepository(ClasseSurveillant::class)->findOneBy(['idUtilisateur'=>$surveillant, 'idClasse'=>$classe,'statut'=>1]);
                if($verif != null)
                {
                    $this->addFlash('ERREUR', "La classe " .$classe->getCodeClasse(). " à déjà étè attribuée à " .$surveillant->getNomUtilisateur(). " " .$surveillant->getPrenomUtilisateur());
                    return $this->redirectToRoute('attr_surveillant_classe');
                }

                $surv_classe = new ClasseSurveillant();
                $surv_classe->setIdUser($user);
                $surv_classe->setIdUtilisateur($surveillant);
                $surv_classe->setIdClasse($classe);
                $surv_classe->setIdAnnee($annee);
                $surv_classe->setDateAjout(new \DateTime());
                $surv_classe->setResponsable(0);
                $surv_classe->setStatut(1);

                $manager->persist($surv_classe);
            }
            $manager->flush();
            $this->addFlash('SUCCESS', "Attribution éffectuée avec succès.");
            return $this->redirectToRoute('attr_surveillant_classe');
        }
        return $this->render('admin/utilisateur/attr_surveillant_classe.html.twig',[
            'surveillants' => $surveillants,
            'classes' => $classes,
        ]);
    }

    /**
     * @Route("/detail", name="detail")
     */
    public function detail(Request $request,EntityManagerInterface $manager,?UserInterface $user)
    {
    
        if ($request->isXmlHttpRequest())
        {  
            $id = $request->request->get('id'); 
            $retour ="";

            $attribution=$this->getDoctrine()->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$id, 'statut'=>1]);

            if ($attribution != null) 
            {
                foreach ($attribution as $enreg ) 
                {
                    $id=$enreg->getIdClasseSurveillant();
                    $code=$enreg->getIdClasse()->getCodeClasse();
                    $libelle=$enreg->getIdClasse()->getLibelleClasse();
                  
                    $retour .= "
                                    
                    <div class='pretty p-icon p-curve p-tada'>
                    <input type='checkbox' checked='checked' disabled>
                    <div class='state p-primary-o'>
                    <i class='icon material-icons'>done</i>
                    <label><strong>$code</strong></label>
                    </div>
                    </div> ";
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
     * @Route("/desattribuer", name="desattribuer")
     */
    public function desattribuer(Request $request,EntityManagerInterface $manager,?UserInterface $user)
    {
        $surveillants = "";

        if($user->getIdProfil()->getIdProfil()==1)
        {
            $utilisateurs = $manager->getRepository(Utilisateur::class)->findBy(['idProfil'=>[2,3],'idStatut'=>1]);
            $surveillants = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$utilisateurs,'statut'=>1]);
        }
        if ($user->getIdProfil()->getIdProfil()==3) 
        {
            $utilisateurs = $manager->getRepository(Utilisateur::class)->findBy(['idProfil'=>4,'idStatut'=>1]);
            $surveillants = $manager->getRepository(ClasseSurveillant::class)->findBy(['idUtilisateur'=>$utilisateurs,'statut'=>1]);
        }
        
        return $this->render('admin/utilisateur/desattribuer.html.twig',[
            'surveillants' => $surveillants,
        ]);
    }

    /**
     * @Route("/sup_attr{id}", name="sup_attr")
     */
    public function sup_attr(Request $request,EntityManagerInterface $manager,$id=null)
    {
        $sup = $manager->getRepository(ClasseSurveillant::class)->findOneBy(['idClasseSurveillant'=>$id, 'statut'=>1]);
        $sup->setStatut(0);
        $manager->persist($sup);
        $manager->flush();
        $this->addFlash('SUCCESS', "Désattribution éffectuée avec succès.");
        return $this->redirectToRoute('desattribuer');
    }

        /**
     * @Route("/responsable{id}_{classe}_{prof}", name="responsable")
     */
    public function responsable(Request $request,EntityManagerInterface $manager,$id=null,$classe=null,$prof=null)
    {
        
        $respond = $manager->getRepository(ClasseSurveillant::class)->findOneBy(['idClasseSurveillant'=>$id, 'statut'=>1]);
        //dd($respond);
        $oui = $respond->getResponsable();
        if($oui == 1)
        {
            $respond->setResponsable(0);
            $manager->persist($respond);
            $this->addFlash('SUCCESS', "La Déresponsabilisation à étè éffectuée avec succès.");
        }else
        {
            $verif = $manager->getRepository(ClasseSurveillant::class)->findOneBy(['idClasse'=>$classe,'responsable'=>1,'idAnnee'=>1,'statut'=>1]);
            //dd($verif);
            if($verif == null)
            {
                $respond->setResponsable(1);
                $manager->persist($respond);
                $this->addFlash('SUCCESS', "La Responsabilisation à étè éffectuée avec succès.");
            }else
            {
                $this->addFlash('ERREUR', "Impossible de responsabiliser");
            }
        }

        $manager->flush();

        // if($oui == 1)
        // {
        //     $this->addFlash('SUCCESS', "La Déresponsabilisation à étè éffectuée avec succès.");
        // }else
        // {
        //     $this->addFlash('SUCCESS', "La Responsabilisation à étè éffectuée avec succès.");
        // }
        
        return $this->redirectToRoute('desattribuer');
        
    }
}