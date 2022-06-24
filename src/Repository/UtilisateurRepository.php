<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Utilisateur;

/* UtilisateurRepository
*
*This class was genereted by the Doctrine ORM. Add your own custom
* repository methods below
*/

class UtilisateurRepository extends EntityRepository
{
    public function verifLoginUtilisateurModif($id,$login)
    {
        $utilisateur= $this->_em->createQueryBuilder();
        $utilisateur->select('u')
            ->from(Utilisateur::class,'u')
            ->where('u.idUtilisateur != :id and u.login = :login and (u.idStatut = 1 or u.idStatut = 2) and u.statut = 1') // la condition
            ->setParameters(array(
                'id'=>$id,
                'login'=>$login,
            ));        
        return $utilisateur->getQuery()
                    ->getResult(); /// plusieurs resultats                         
    }

    //     public function getDepot($recherchedate1, $recherchedate2, $recherchedepot, $rechercherestitution, $recherchepaiement)
    // {
    //     $avec_depot='';
    //     if (strlen($recherchedepot) > 0) {
    //         $avec_depot="and d.idTypeDepot='$recherchedepot'";
    //     }else{
    //         $avec_depot="and (d.idTypeDepot = 1 or d.idTypeDepot = 2 or d.idTypeDepot = 3)";  
    //     }
    //     $avec_rest='';
    //     if (strlen($rechercherestitution) > 0) {
    //         $avec_rest="and r.idStatut='$rechercherestitution'";  
    //     }else{
    //         $avec_rest="and (r.idStatut = 1 or r.idStatut = 2 or r.idStatut = 3)";  
    //     }
    //     $avec_paie='';
    //     if (strlen($recherchepaiement) > 0) {
    //         $avec_paie="and d.statutPaiement='$recherchepaiement'";  
    //     }else{
    //         $avec_paie="and (d.statutPaiement = 1 or d.statutPaiement = 2)";  
    //     }


    //     $depot= $this->_em->createQueryBuilder();
    //     $depot->select('d')
    //           ->from(Depot::class,'d')
    //           ->innerJoin('d.idTypeDepot', 't')
    //           ->innerJoin('d.idStatut', 'r')
    //           ->where("d.dateDepot BETWEEN :recherchedate1 AND :recherchedate2 and d.statutDepot = 1 
    //                    $avec_depot $avec_rest $avec_paie") // la condition
    //           ->setParameters(array(
    //                             'recherchedate1'=>$recherchedate1,
    //                             'recherchedate2'=>$recherchedate2
    //                          ));
    //           return $depot->getQuery()
    //                        ->getResult(); /// un seul resultat ou null
                                
    // }
    // public function getDepotParDate($recherchedate)
    // {
       
    //              $depot= $this->_em->createQueryBuilder();
    //              $depot->select('d')
    //                      ->from(Depot::class,'d')
    //                      ->where('d.statutDepot = :statut and d.dateDepot LIKE :date_depot ') // la condition
    //                          ->setParameters(array(
    //                             'statut'=>1,
    //                             'date_depot'=>$recherchedate.'%',
    //                          ));
                              
    //               return $depot->getQuery()
    //                               ->getResult(); /// un seul resultat ou null
                                
    // }
    // public function getDepotParDaterdv($recherchedate)
    // {
       
    //              $depot= $this->_em->createQueryBuilder();
    //              $depot->select('d')
    //                      ->from(Depot::class,'d')
    //                      ->where('d.statutDepot = :statut and d.dateRdv LIKE :date_rdv ') // la condition
    //                          ->setParameters(array(
    //                             'statut'=>1,
    //                             'date_rdv'=>$recherchedate.'%',
    //                          ));
                              
    //               return $depot->getQuery()
    //                               ->getResult(); /// un seul resultat ou null
                                
    // }
    //     public function getDepotParDateRdvNew($date_debut, $date_fin, $recherchedepot)
    // {
    //     $avec_depot='';
    //     if (strlen($recherchedepot) > 0) {
    //         $avec_depot="and d.idTypeDepot='$recherchedepot'";  
    //     }else{
    //         $avec_depot="and (d.idTypeDepot = 1 or d.idTypeDepot = 2 or d.idTypeDepot = 3)";  
    //     }
       
        
        
    //     $depot= $this->_em->createQueryBuilder();
    //     $depot->select('d')
    //           ->from(Depot::class,'d')
    //           // ->innerJoin('d.idTypeDepot', 't')
    //           ->where("d.dateRdv BETWEEN :date_debut AND :date_fin and d.statutDepot = 1 
    //                    $avec_depot") // la condition
    //           ->setParameters(array(
    //                             'date_debut'=>$date_debut,
    //                             'date_fin'=>$date_fin
    //                          ));
                      
    //     return $depot->getQuery()
    //                  ->getResult(); /// un seul resultat ou null
                                
    // }
    // public function getDepotParRdv_client($rechercherdv,$rechercheclient)
    // {
    //     $depot= $this->_em->createQueryBuilder();
    //              $depot->select('d')
    //                      ->from(Depot::class,'d')
    //                      ->innerJoin('d.idClient', 'c')
    //                      ->where('d.statutDepot = :statut and d.numeroRecu =:numero and d.dateRdv LIKE :date_rdv') // la condition
    //                          ->setParameters(array(
    //                             'statut'=>1,
    //                             'date_rdv'=>$rechercherdv.'%',
    //                             'numero'=>$rechercheclient,
    //                          ));
                             
                              
    //               return $depot->getQuery()
    //                               ->getOneOrNullResult();
    // }
    // /**************************** Ismael ******************************/
    // public function recherche($num,$tel)
    // {
    //     $num1='';
    //     if ($num != null) {
    //     //filtre avec direction
    //      if(strlen($num)>0) $num1="and d.numeroRecu ='$num'";
    //     }

    //     $tel1='';
    //     if ($tel != null) {
    //     //filtre avec direction
    //      if(strlen($tel)>0) $tel1="and c.telClient = '$tel'";
    //     }

    //     $depot= $this->_em->createQueryBuilder();
    //     $depot->select('d')
    //             ->from(Depot::class,'d')
    //             ->innerJoin('d.idClient', 'c')
    //             ->where("d.statutDepot = 1 and (d.idStatut = 2 or d.idStatut = 3) $num1 $tel1")
    //             ->orderBy('d.idDepot','DESC'); // la condition 
    //     return $depot->getQuery()
    //                     ->getResult(); /// un seul resultat ou null
    // }

    // public function sommeArticle($depot)
    // {
    //     $depot= $this->_em->createQueryBuilder();
    //     $depot->select('COALESCE(SUM(d.nbreArticle),0)')
    //             ->from(DetailDepot::class,'d')
    //             ->where("d.idDepot = :depot")
    //             ->setParameters(array(
    //                 'depot'=>$depot,
    //              )); // la condition 
    //     return $depot->getQuery()
    //                 ->getSingleScalarResult(); /// un seul resultat ou null
    // }
}
