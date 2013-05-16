<?php

namespace Metinet\Bundle\FacebookBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getClassement($id){
        
        $query = $this->getEntityManager()
        ->createQuery('
            SELECT u FROM MetinetFacebookBundle:User u
            ORDER BY u.points DESC'
        );

        try {
            $users = $query->getResult();
            
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
        
        if(is_object($users)){
            
            return 1;
        }
        
        $i = 1;
        foreach ($users as $user){
            
            if($user->getFbUid() == $id){
                
                return $i;
            }
            $i ++;
        }
    }
    
    public function getAllUsers() {
    	
    	$query = $this->getEntityManager()
            ->createQuery('
                SELECT count(a) FROM MetinetFacebookBundle:User a'
            );
    	try {
    		$result = $query->getSingleResult();
    	} catch (\Doctrine\ORM\NoResultException $e) {
    		return null;
    	}
    	$bal = $result[1];
    	return $bal;
    }
    
    public function getDateAllUsers($date) {
    	 
            $query = $this->getEntityManager()
            ->createQuery('
                SELECT count(a) FROM MetinetFacebookBundle:User a
                WHERE a.createdAt >= :created_at'
            )->setParameter('created_at', $date);

            try {
                $result = $query->getSingleResult();
            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }
    	$bal = $result[1];
    	return $bal;
    }
    
    public function getClassementAvecAmis($friends,$idUser){    
    
        if($friends == null){
            
            $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM MetinetFacebookBundle:User u
                ORDER BY u.points DESC'
            )->setFirstResult($idUser - 2)
             ->setMaxResults(4);

            try {
                $Userstmp = $query->getResult();
                foreach($Userstmp as $tmp){
                    $users[] = Array('id' => $tmp->getId(), 'firstname' => $tmp->getFirstname(), 'lastname' => $tmp->getLastname(), 'picture' => $tmp->getPicture(), 'points' => $tmp->getPoints());
                }

            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }              
            
            return $users;
        }
        
        else{
            
            $i = 0;
            foreach ($friends as $friend){
                
                $query = $this->getEntityManager()
                ->createQuery('
                SELECT u FROM MetinetFacebookBundle:User u
                WHERE u.fbUid = :id'
                )->setParameter('id',$friend);
                try {
                    $tmp = $query->getSingleResult();
                    if($tmp->getPoints() != 0 && $tmp->getPoints() != null){
                        $users[$i] = Array('id' => $tmp->getId(), 'firstname' => $tmp->getFirstname(), 'lastname' => $tmp->getLastname(), 'picture' => $tmp->getPicture(), 'points' => $tmp->getPoints());
                    }

                } catch (\Doctrine\ORM\NoResultException $e) {
                    return null;
                }
                
                $i ++;
            }
            
            $query = $this->getEntityManager()
            ->createQuery('
            SELECT u FROM MetinetFacebookBundle:User u
            WHERE u.id = :id'
            )->setParameter('id',$idUser);
            try {
                $tmp = $query->getSingleResult();
                $userTmp = $tmp;
                
                if($tmp->getPoints() == null){
                   $tmp->setPoints(0);
                }
                $users[$i]= Array('id' => $tmp->getId(), 'firstname' => $tmp->getFirstname(), 'lastname' => $tmp->getLastname(), 'picture' => $tmp->getPicture(), 'points' => $tmp->getPoints());       
                
            } catch (\Doctrine\ORM\NoResultException $e) {
                return null;
            }
            
            $users = $this->sort_by_key($users, 'points');
            
            foreach ($users as $user){
                if($user['lastname'] == $userTmp->getLastname() && $user['firstname'] == $userTmp->getFirstname()){
                    $newIdUser = $user['id'];
                }
            }
            
            
            $i = 0;
            foreach ($users as $user){
                if($i < $newIdUser -2 && $i > $newIdUser + 2){
                    unset($user[$i]);
                }
            }
            
            return $users;
        }
        
    }
    
    function sort_by_key($array, $index, $desc = 1) {
        $sort = array();

        //préparation d'un nouveau tableau basé sur la clé à trier
        foreach ($array as $key => $val) {
            $sort[$key] = $val[$index];
        }

        //tri par ordre naturel et insensible à la casse
        if($desc == 1){
            arsort($sort);
        }
        else{
            asort($sort);
        }
        

        //formation du nouveau tableau trié selon la clé
        $output = array();

        foreach($sort as $key => $val) {
            $output[] = $array[$key];
        }

        return $output;
    }
    
}
