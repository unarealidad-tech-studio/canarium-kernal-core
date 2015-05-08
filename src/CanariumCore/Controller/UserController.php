<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CanariumCore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends \ZfcUser\Controller\UserController
{
	public function submissionListAction(){

	}

	public function submittedFormAction(){
		$id = (int) $this->params()->fromRoute('id', 0);

		$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$form = new \Form\Form\FieldsetForm($objectManager);

		$data = $objectManager->getRepository('Form\Entity\ParentData')->find($id);

		$view = new ViewModel();
		$view->data = $data;
		return $view;
	}

}
