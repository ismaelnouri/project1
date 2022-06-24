<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Classe;

/* UtilisateurRepository
*
*This class was genereted by the Doctrine ORM. Add your own custom
* repository methods below
*/

class VerifRepository extends EntityRepository
{
    public function verifClasse($id,$code)
    {
        $classe= $this->_em->createQueryBuilder();
        $classe->select('c')
            ->from(Classe::class,'c')
            ->where('c.idClasse != :id and c.codeClasse = :code and c.statut = 1') // la condition
            ->setParameters(array(
                'id'=>$id,
                'code'=>$code,
            ));        
        return $classe->getQuery()
                    ->getResult(); /// plusieurs resultats                         
    }
}