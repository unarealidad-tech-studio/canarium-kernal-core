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

    public function updateUser($user, array $data)
    {
        $form = $this->getServiceLocator()->get('canariumcore_user_form');

        $validatePassword = true;
        if ($data['passwordVerify'] == '' && $data['password'] == '') {
            $validatePassword = false;
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $validatorParams = array(
            'user' => $user,
            'key' => 'email',
            'entityManager' => $objectManager
        );

        $validator = new \CanariumCore\Validator\NoUserExists($validatorParams);
        $inputFilter = new \CanariumCore\InputFilter\UserRuntimeFilter($validator, $validatePassword);
        $form->setInputFilter($inputFilter);

        $oldPassword = $user->getPassword();

        $form->bind($user);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }

        // Remove existing roles to avoid duplicates
        $currentRoles = $user->getRoles(false);
        $user->removeRole($currentRoles);

        // Add new roles
        foreach ($data['role'] as $roleId) {
            $role = $objectManager->getRepository('CanariumCore\Entity\Role')->find($roleId);
            $user->addRole($role);
        }

        // Save password
        if ($user->getPassword() != '') {
            $options = $this->getServiceLocator()->get('zfcuser_module_options');
            $bcrypt = new \Zend\Crypt\Password\Bcrypt();
            $bcrypt->setCost($options->getPasswordCost());
            $user->setPassword($bcrypt->create($user->getPassword()));
        } else {
            $user->setPassword($oldPassword);
        }

        $objectManager->flush();
        return $user;
    }

    public function getHighestRole(CanariumUser $user)
    {
        $roles = $user->getRoles();

        $highestRoleId = 0;
        $output = null;
        foreach ($roles as $role) {
            if ($role->getId() > $highestRoleId) {
                $output = $role;
                $highestRoleId = $role->getId();
            }
        }

        return $output;
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
