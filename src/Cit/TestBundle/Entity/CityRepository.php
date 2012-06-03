<?php

namespace Cit\TestBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CityRepository
 *
 * Add your own custom repository methods below.
 */
class CityRepository extends EntityRepository
{
	public function myCities($term)
	{
	    $qb = $this->_em->createQueryBuilder();

	    $qb->select('a.frenchname')
	       ->from('CitTestBundle:City', 'a')
	       ->where("a.frenchname LIKE '".$term."%'")
	       ->orderBy('a.frenchname', 'ASC');
	       //->setLimit(20)
		
	    $requete = $qb->getQuery();
	    
	    return $requete->getResult();
	}

	public function isCity($city)
	{
		$qb = $this->_em->createQueryBuilder();
	    $qb->select('a.frenchname')
	    	->from('CitTestBundle:City', 'a')
	    	->where('a.frenchname = :city')
	    	->setParameter('city',$city);

	    $requete = $qb->getQuery();

	    return (null != $requete->getResult());
	}
}