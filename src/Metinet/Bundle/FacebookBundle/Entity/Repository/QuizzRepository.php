<?php

namespace Metinet\Bundle\FacebookBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuizzRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuizzRepository extends EntityRepository
{
    public function getNbUserByQuizz($id)
    {
        $query = $this->getEntityManager()
        ->createQuery('
            SELECT q FROM MetinetFacebookBundle:QuizzResult q
            WHERE q.quizz = :id'
        )->setParameter('id', $id);

        try {
            return $query->getResult();
            
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
        
    }
    
    public function getTauxReussiteMoyen($nbQuizzResult,$id)
    {
        if(count($nbQuizzResult) != 0){
            $query = $this->getEntityManager()
            ->createQuery('
                SELECT q FROM MetinetFacebookBundle:Quizz q
                WHERE q.id = :id'
            )->setParameter('id', $id);

            try {
                $quizz = $query->getSingleResult();
            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }

            $totalPoints = $quizz->getWinPoints();

            $tauxReussite = 0;

            foreach($nbQuizzResult as $quizzResult){

                $userPoints = $quizzResult->getWinPoints();
                $tauxReussite += ($userPoints * 100) / $totalPoints;

            }

            return $tauxReussite / count($nbQuizzResult);
        }
        else
        {
            return 0;
        }
        

    }
    
    public function quatreDernierQuizz()
    {
        $query = $this->getEntityManager()
        ->createQuery('
            SELECT q FROM MetinetFacebookBundle:Quizz q
            ORDER BY q.id ASC'
        )->setMaxResults(4);
        
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
    public function dernierQuizzPromo()
    {
        $query = $this->getEntityManager()
        ->createQuery('
            SELECT q FROM MetinetFacebookBundle:Quizz q
            WHERE q.isPromoted = 1'
        )->setMaxResults(1);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }

    }   
    
    public function getTotQuizz() {    	
    	$query = $this->getEntityManager()
    	->createQuery('
                SELECT count(q) FROM MetinetFacebookBundle:Quizz q'
    	);
    	try {
    		$result = $query->getSingleResult();
    	} catch (\Doctrine\ORM\NoResultException $e) {
    		return null;
    	}
    	$bal = $result[1];
    	return $bal;
    	
    
    }
}
