<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Note;
use App\Entity\NoteSemestrielle;
use App\Entity\Matiere;
use App\Entity\Inscription;
use App\Entity\Bulletin;

/* UtilisateurRepository
*
*This class was genereted by the Doctrine ORM. Add your own custom
* repository methods below
*/

class NoteRepository extends EntityRepository
{
    public function NoteDevoirMax($id,$semestre,$annee)
    {
        $note= $this->_em->createQueryBuilder();
        $note->select('MAX (n.note) as max_note')
            ->from(Note::class,'n')
            ->where('n.idEleve = :id and (n.idTypeDevoir = 1 or n.idTypeDevoir = 2) and n.idAnnee = :annee and n.idSemestre = :semestre and n.statut = 1') // la condition
            ->setParameters(array(
                'id'=>$id,
                'semestre'=>$semestre,
                'annee'=>$annee,
            ));        
        return $note->getQuery()
                    ->getOneOrNullResult(); /// plusieurs resultats                         
    }
    public function NoteDevoirMoyenne($id,$semestre,$annee)
    {
        $note= $this->_em->createQueryBuilder();
        $note->select('(SUM (n.note) / COUNT (n.idNote) ) as moyenne_note')
            ->from(Note::class,'n')
            ->where('n.idEleve = :id and (n.idTypeDevoir = 1 or n.idTypeDevoir = 2) and n.idAnnee = :annee and n.idSemestre = :semestre and n.statut = 1') // la condition
            ->setParameters(array(
                'id'=>$id,
                'semestre'=>$semestre,
                'annee'=>$annee,
            ));        
        return $note->getQuery()
                    ->getOneOrNullResult(); /// plusieurs resultats                         
    }

    public function Total($id,$semestre,$annee)
    {
        $note= $this->_em->createQueryBuilder();
        $note->select('SUM(((ns.noteClasse)+(ns.noteCompo))/2)*ns.coefficient as total')
            ->from(NoteSemestrielle::class,'ns')
            ->where('ns.idEleve = :id and ns.idAnnee = :annee and ns.idSemestre = :semestre and ns.statut = 1') // la condition
            ->setParameters(array(
                'id'=>$id,
                'semestre'=>$semestre,
                'annee'=>$annee,
            ));        
        return $note->getQuery()
                    ->getOneOrNullResult(); /// plusieurs resultats                         
    }

    public function CoefficientParNiveau($id)
    {
        $note= $this->_em->createQueryBuilder();
        $note->select('SUM (m.coefficientMatiere) as coefficient')
            ->from(Matiere::class,'m')
            ->where('m.idNiveau = :id and m.statut = 1') // la condition
            ->setParameters(array(
                'id'=>$id,
            ));        
        return $note->getQuery()
                    ->getOneOrNullResult(); /// plusieurs resultats                         
    }

    public function NbreEleveParClasse($ids,$annee)
    {
        $note= $this->_em->createQueryBuilder();
        $note->select('COALESCE(COUNT (i.idInscription),0)')
            ->from(Inscription::class,'i')
            ->where('i.idEleve IN (:ids) and i.idAnnee = :annee and i.statut = 1') // la condition
            ->setParameters(array(
                'ids'=>$ids,
                'annee'=>$annee,
            ));        
        return $note->getQuery()
                    ->getSingleScalarResult(); /// un entier                         
    }

    public function SommeMoyenneClasse($ids,$semestre,$annee)
    {
        $note= $this->_em->createQueryBuilder();
        $note->select('COALESCE(SUM (b.moyenne),0)')
            ->from(Bulletin::class,'b')
            ->where('b.idEleve IN (:ids) and b.idAnnee = :annee and b.idSemestre = :semestre and b.statut = 1') // la condition
            ->setParameters(array(
                'ids'=>$ids,
                'semestre'=>$semestre,
                'annee'=>$annee,
            ));        
        return $note->getQuery()
                    ->getSingleScalarResult(); /// plusieurs resultats                         
    }
}