<?php

namespace CanariumCore\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use CanariumCore\Entity\User as CanariumUser;

class User implements ServiceLocatorAwareInterface
{
    protected $objectManager;

    public function countUsers()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->select('COUNT(u)')
                     ->from('CanariumCore\Entity\User', 'u');

        $query = $queryBuilder->getQuery();
        return $query->getSingleScalarResult();
    }

    public function isMaximumUserReached()
    {
        $option = $this->getServiceLocator()->get('canariumsettings_user_options');
        return $option->getUserCreationLimit() > 0 && $option->getUserCreationLimit() <= $this->countUsers();
    }

    public function removeUser(CanariumUser $user)
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
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
