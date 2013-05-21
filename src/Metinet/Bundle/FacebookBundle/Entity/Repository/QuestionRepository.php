<?php

namespace Metinet\Bundle\FacebookBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends EntityRepository
{
	
	public function getCountQuestionsByQuizz($id) {
		$query = $this->getEntityManager()
		->createQuery('
                SELECT count(q) FROM MetinetFacebookBundle:Question q
				WHERE q.quizz = :quizz_id'
		)->setParameter('quizz_id', $id);
		try {
			$result = $query->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
		$bal = $result[1];
		return $bal;
	}
}
