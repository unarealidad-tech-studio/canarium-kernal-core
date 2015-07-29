<?php 

namespace CanariumCore\Widget;
use Settings\Widget\CanariumWidgetInterface;
use Zend\View\Model\ViewModel;

class TotalUsers implements CanariumWidgetInterface
{
	protected $templatePath = 'canarium-core/admin/widget/total-users';
	protected $serviceLocator;

	public function __construct(\Zend\ServiceManager\ServiceManager $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function getView() 
	{	
		$view = new ViewModel(array( 'totalUsers' => $this->getTotalUsers() ));
		$view->setTemplate( $this->templatePath );
		return $view;
	}

	public function getTotalUsers()
	{
		$em = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
		$query = $em->createQuery('SELECT COUNT(u) FROM CanariumCore\Entity\User u');
		$total = $query->getSingleScalarResult();
		return $total;
	}
}