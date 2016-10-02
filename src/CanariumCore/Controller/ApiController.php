<?php

namespace CanariumCore\Controller;

use CanariumCore\Exception\InvalidTokenException;
use CanariumCore\Exception\InvalidUserException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractActionController
{
    const RequestSuccess        = 1;
    const InvalidUser           = 2;
    const FailedCreatingToken   = 3;
    const InvalidRequestMethod  = 4;
    const RegistrationFailed    = 5;
    const InvalidAccessToken    = 6;
    const UnidentifiedError     = 0;


    public $responseCodes = array(
        1 => array('status' => 'ok',     'message' => 'Request successful'),
        2 => array('status' => 'error',  'message' => 'Invalid user'),
        3 => array('status' => 'error',  'message' => 'Failed creating access token'),
        4 => array('status' => 'error',  'message' => 'Invalid request method'),
        5 => array('status' => 'error',  'message' => 'Registration failed'),
        6 => array('status' => 'error',  'message' => 'Invalid access token'),
        0 => array('status' => 'error',  'message' => 'Unidentified error occurred'),
    );

    public $entityManager;

    public function indexAction()
    {
        die('index');
    }

    public function loginAction()
    {
        $appId      = $this->getRequest()->getPost('id');       // test: 1;
        $appSecret  = $this->getRequest()->getPost('secret');   // test: '50b9d04e28e1380bf522a7430b7a9b5c08a8cc15';
        $email      = $this->getRequest()->getPost('email');    // test: 'kevin.mirafuentes@yahoo.com';
        $appService = $this->getServiceLocator()->get('canariumcore_app_service');

        if (!$appId || !$appSecret || !$email) {
            return $this->response(self::FailedCreatingToken, array(), 'Invalid request');
        }

        try {
            $token = $appService->authenticate($appId, $appSecret)
                ->createToken($email);
            if ($token) {
                $accessToken = $token->getAccessToken();
                $expiryDate  = $token->getExpiryDate();
                $user        = $token->getUser();

                $output['access_token'] = $accessToken;
                $output['expiry_date']  = $expiryDate ? $expiryDate->format(\DateTime::ATOM) : '';
                $output['name']         = $user->getDisplayName();
                $output['email']        = $user->getEmail();

                return $this->response(self::RequestSuccess, $output);
            }

            return $this->response(self::FailedCreatingToken);

        } catch (InvalidUserException $e) {

            return $this->response(self::InvalidUser);

        } catch (\Exception $e) {
            return $this->response(self::UnidentifiedError);
        }
    }

    public function deleteAccountAction()
    {
        $data = $this->getRequest()->getPost();

        try {
            if ($this->getRequest()->isPost() && $data) {
                $accessToken = $data['access_token'];
                $userService = $this->getUserService();
                $user = $userService->getUserByAccessToken($accessToken);

                if (!$user) {
                    return $this->response(self::InvalidUser);
                }

                $em = $this->getEntityManager();
                $em->remove($user);
                $em->flush();

                return $this->response(self::RequestSuccess);
            } else {
                return $this->response(self::InvalidRequestMethod);
            }
        } catch (InvalidTokenException $e) {
            return $this->response(self::InvalidAccessToken);
        } catch (\Exception $e) {
            return $this->response(self::UnidentifiedError);
        }
    }

    public function response($code, $data=array(), $message = null)
    {
        $response = $this->responseCodes[$code];

        if ($message) {
            $response['message'] = $message;
        }

        $response['code'] = $code;
        $response['data'] = $data;
        return new JsonModel($response);
    }

    public function getUserService()
    {
        return $this->getServiceLocator()->get('canariumcore_user_service');
    }

    public function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->setEntityManager();
        }
        return $this->entityManager;
    }

    public function setEntityManager($em=null)
    {
        if ($em) {
            $this->entityManager = $em;
        } else {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
    }
}
