<?php

namespace CanariumCore\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use CanariumCore\Entity\User as CanariumUser;

class User implements ServiceLocatorAwareInterface
{
    protected $objectManager;

    public function countFrontendUsers()
    {
        $em = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
        $role = $em->getRepository('CanariumCore\Entity\Role')->find(1);

        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->select('COUNT(u)')
                     ->from('CanariumCore\Entity\User', 'u')
                     ->andWhere(':role MEMBER OF u.roles')
                        ->setParameter('role', $role)
                     ->andWhere('u.lastLogin IS NOT NULL')
                     ->orderBy('u.lastLogin', 'DESC');

        $query = $queryBuilder->getQuery();
        return $query->getSingleScalarResult();
    }

    public function removeUser(CanariumUser $user)
    {
        $em = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
        $em->remove($user);
        $em->flush();
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}
