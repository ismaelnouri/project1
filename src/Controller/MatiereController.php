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
use App\Entity\Matiere;
use App\Entity\TypeMatiere;
use App\Entity\Niveau;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use League\Csv\Writer;
use League\Csv\Reader;


class MatiereController extends AbstractController
{
    /**
     * @Route("/index/matiere", name="listematiere")
     */
    public function index(Request $request, EntityManagerInterface $manager)
    {
        // if ($user && $this->getUser()->getStatutEmp() == 1 )
        // {
        // $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        $matieres = $manager->getRepository(Matiere::class)->findBy(['statut'=>1]);

        return $this->render('admin/matiere/liste.html.twig',[
            'matieres' => $matieres
        ]);
       // }else
       //  {
       //   return $this->redirectToRoute('deconnexion');
       //  }
    }

    /**
     * @Route("/ajout", name="ajoutmatiere")
     */
    public function ajout(Request $request, EntityManagerInterface $manager)
    {
        // if ($user && $this->getUser()->getStatutEmp() == 1 )
        // {
        // $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        $niveaux = $manager->getRepository(Niveau::class)->findBy(['statut'=>1]);
        if($request->request->count()>0)
        {
            $file = $request->files->get('fichier');
            $idNiveau = $request->request->get('niveau');
            //dd($file,$idNiveau);
            if($file == null || $idNiveau == '')
            {
                $this->addFlash('ERREUR', "Merci de renseigner tous les champs!");
                return $this->redirectToRoute('ajoutmatiere');
            }
        
            $select_niveau = $manager->getRepository(Niveau::class)->findOneBy(['idNiveau'=>$idNiveau,'statut'=>1]);
            

            $stream = fopen($file,'r');
            $csv = Reader::createFromStream($stream);
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
            
            //dd($csv);
            foreach($csv as $row)
            {
                //dd($row);
                $select_type_matiere = $manager->getRepository(TypeMatiere::class)->findOneBy(['codeTypeMatiere'=>$row['code'],'statut'=>1]);

                $verif = $manager->getRepository(Matiere::class)->findOneBy(['idNiveau'=>$idNiveau, 'idTypeMatiere'=>$select_type_matiere,'codeMatiere'=>$row['code'],'libelleMatiere'=>$row['libelle'],'coefficientMatiere'=>$row['coefficient'],'statut'=>1]);
                if($verif != null)
                {
                    $this->addFlash('ERREUR', "Existe déjà!");
                    return $this->redirectToRoute('ajoutmatiere');
                }

                $matiere = new Matiere();
                $matiere->setIdNiveau($select_niveau);
                $matiere->setIdTypeMatiere($select_type_matiere);
                $matiere->setCodeMatiere($row['code']);
                $matiere->setLibelleMatiere($row['libelle']);
                $matiere->setCoefficientMatiere($row['coefficient']);
                $matiere->setDateAjout(new \DateTime());
                $matiere->setStatut(1);
                $manager->persist($matiere);
                //dump($matiere);
            }
           // die();
            $manager->flush();
            $this->addFlash('SUCCESS', "Les matiéres du niveau ".$select_niveau->getLibelleNiveau()." ont étè ajoutées avec succes.");
            return $this->redirectToRoute('listematiere');
        }
        return $this->render('admin/matiere/ajout.html.twig',[
            'niveaux' => $niveaux
        ]);
       // }else
       //  {
       //   return $this->redirectToRoute('deconnexion');
       //  }
    }

    /**
     * @Route("/annuler", name="annulermatieres")
     */
    public function annuler(Request $request, EntityManagerInterface $manager)
    {
        // if ($user && $this->getUser()->getStatutEmp() == 1 )
        // {
        // $utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        $niveaux = $manager->getRepository(Niveau::class)->findBy(['statut'=>1]);
        

        if($request->request->count()>0)
        {
            $idNiveau = $request->request->get('niveau');
            // $niveau = $manager->getRepository(Niveau::class)->find($idNiveau);
            //dd($file,$idNiveau);
            if($idNiveau == '')
            {
                $this->addFlash('ERREUR', "Merci de renseigner le champs!");
                return $this->redirectToRoute('annulermatieres');
            }
        
            $matieres = $manager->getRepository(Matiere::class)->findBy(['idNiveau'=>$idNiveau,'statut'=>1]);
            
            if($matieres)
            {
                //dd($csv);
                foreach($matieres as $matiere)
                {
                    $matiere->setStatut(0);
                    $manager->persist($matiere);
                    //dump($matiere);
                }
                // die();
                $manager->flush();
                $this->addFlash('SUCCESS', "Les matiéres du niveau ".$matiere->GetLibelleMatiere()." ont étè annulées avec succes.");
                return $this->redirectToRoute('listematiere');
            }else
            {
                $this->addFlash('ERREUR', "Aucune matiere de ce niveau trouvé.");
                return $this->redirectToRoute('annulermatieres');
            }
        }
        return $this->render('admin/matiere/annule.html.twig',[
            'niveaux' => $niveaux
        ]);
       // }else
       //  {
       //   return $this->redirectToRoute('deconnexion');
       //  }
    }

}