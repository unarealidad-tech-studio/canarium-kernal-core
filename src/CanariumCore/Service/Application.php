<?php

namespace CanariumCore\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use CanariumCore\Entity\AccessToken;

class Application implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    protected $moduleOptions;
    protected $objectManager;
    protected $currentApplicationInstance;

    public function authenticate($appId, $appSecret) 
    {
        $em = $this->getObjectManager();
        $application = $em->getRepository('CanariumCore\Entity\Application')
                          ->findOneBy(array('id' => $appId, 'app_secret' => $appSecret));    

        $this->currentApplicationInstance = $application;
        return $this;
    }

    public function createToken($email) 
    {
        $token  = '';
        $em     = $this->getObjectManager();
        $user   = $em->getRepository('CanariumCore\Entity\User')->findOneBy(array('email' => $email));

        if (!$user) {
            throw new \CanariumCore\Exception\InvalidUserException();
        }

        if ($user && $this->currentApplicationInstance) {
            $criteria = new \Doctrine\Common\Collections\Criteria();
            $criteria->where($criteria->expr()->eq('user', $user));
            $criteria->andWhere($criteria->expr()->gte('expiry_date', new \DateTime()));

            $accessToken = $em->getRepository('\CanariumCore\Entity\AccessToken')->matching($criteria)->first();

            if (!$accessToken) {
                $hash   = $this->getModuleOptions()->getApplicationHash();
                $secret = $this->currentApplicationInstance->getAppSecret();
                $token  = sha1($hash . $secret . $email . time());

                $accessToken = new AccessToken();
                $accessToken->setAccessToken($token);
                $accessToken->setUser($user);
                $accessToken->setExpiryDate(new \DateTime('now + 3 years'));

                $em->persist($accessToken);
            }

            $em->flush();

            return $accessToken;
        }
        return false;
    }

    public function getUserByAccessToken($token)
    {
        $em = $this->getObjectManager();
        $accessToken = $em->getRepository('CanariumCore\Entity\AccessToken')->findOneBy(array('access_token' => $token));
        if ($accessToken && $accessToken->getExpiryDate()->getTimestamp() > time()) {
            return $accessToken->getUser();
        }
    }

    public function SSO($token) 
    {
        $user = $this->getUserByAccessToken($token);
        
        if (!$user) {
            return;
        }

        $user->setLastLogin(new \DateTime());
        $this->getObjectManager()->flush();
        
        $zfcUserAuth = $this->getServiceLocator()->get('controllerPluginManager')->get('zfcUserAuthentication');

        // reuse google sso object
        $login = new \GoogleSSO\Authentication\ForceLogin($user);
        $zfcUserAuth->getAuthService()->authenticate( $login );
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getObjectManager() 
    {
        if (! $this->objectManager) {
            $this->setObjectManager();
        }
        return $this->objectManager;
    }

    public function setObjectManager($om = null) 
    {
        $this->objectManager = $om ? $om : $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    public function getModuleOptions()
    {
        if (empty($this->moduleOptions)) {
            $this->setModuleOption();
        }
        return $this->moduleOptions;
    }

    public function setModuleOption($option = null) 
    {
        $this->moduleOptions = $option ? $option : $this->getServiceLocator()->get('canariumcore_module_options');
        return $this;
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
