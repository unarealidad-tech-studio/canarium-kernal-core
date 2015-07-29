<?php 

namespace CanariumCore\Widget;
use Settings\Widget\CanariumWidgetInterface;
use Zend\View\Model\ViewModel;

class LastUserLogin implements CanariumWidgetInterface
{
	protected $templatePath = 'canarium-core/admin/widget/last-user-login';
	protected $serviceLocator;

	public function __construct(\Zend\ServiceManager\ServiceManager $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function getView() 
	{	
		$view = new ViewModel(array( 'user' => $this->getLastUserToLogin() ));
		$view->setTemplate( $this->templatePath );
		return $view;
	}

	public function getLastUserToLogin()
	{
		$em = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
		$role = $em->getRepository('CanariumCore\Entity\Role')->find(1);
		$query = $em->createQuery('SELECT u FROM CanariumCore\Entity\User u WHERE :role MEMBER OF u.roles AND u.lastLogin IS NOT NULL ORDER BY u.lastLogin DESC')
					->setMaxResults(1)
					->setParameter('role', $role);
		$results = $query->getResult();
		if ($results)
			return $results[0];
		return false;
	}
}