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

//use App\Fonctions\Fonctions;

class ConnexionController extends AbstractController
{
    private $session;

      public function __construct(SessionInterface $session)
      {
        $this->session = $session;
      }

    /**
     * @Route("/", name="connexion")
     */
    public function index(Request $request, AuthenticationUtils $authen)
    {
        if($this->getUser()==null){
            $error =$authen->getLastAuthenticationError();
            $login ="";
       
            if ($error) 
            {
                $login= $authen->getLastUsername();
            }
            return $this->render('connexion/index.html.twig', [
                'login'=>$login,
                'error'=>$error,
            ]);

        }else{
          return $this->redirectToRoute('accueil');
        }
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil(Request $request, UserInterface $user): Response
    {
        if($user)
        {
            return $this->redirectToRoute('index');
        }
        
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion(): Response
    {

    }
}
