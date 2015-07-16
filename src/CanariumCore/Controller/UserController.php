<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CanariumCore\Controller;

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

    public function logoutAction()
    {
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        $redirect_route = $this->getOptions()->getLogoutRedirectRoute();

        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            $redirect_route = $redirect;
        }

        $logout_third_party = $this->getServiceLocator()
            ->get('canariumcore_module_options')
            ->isLogoutThirdPartyLoginToo();

        if ($logout_third_party) {
            $uri = $this->getRequest()->getUri();
            $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
            return $this->redirect()->toUrl(
                'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue='.
                $base.$this->url()->fromRoute($redirect_route)
            );
        } else {
            return $this->redirect()->toRoute($redirect_route);
        }
    }

}
