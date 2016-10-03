<?php

namespace CanariumCore\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $site_name = 'Canarium Instance Startup Page';

    protected $verbose_title = false;

    protected $is_authentication_required = false;

    protected $is_authentication_whitelist = array();

    protected $login_on_denied_access = true;

    protected $logout_third_party_login_too = false;

    protected $title_maps = array();

    protected $user_meta = array();

    protected $application_hash = '';

    protected $default_app_id = '';
    protected $default_app_secret = '';

    public function setSiteName($site_name)
    {
        $this->site_name = $site_name;
        return $this;
    }

    public function getSiteName()
    {
        return $this->site_name;
    }

    public function setVerboseTitle($verbose_title)
    {
        $this->verbose_title = $verbose_title;
        return $this;
    }

    public function getVerboseTitle()
    {
        return $this->verbose_title;
    }

    public function setIsAuthenticationRequired($is_authentication_required)
    {
        $this->is_authentication_required = $is_authentication_required;
        return $this;
    }

    public function getIsAuthenticationRequired()
    {
        return $this->is_authentication_required;
    }

    /**
     * @return boolean
     */
    public function isLoginOnDeniedAccess()
    {
        return $this->login_on_denied_access;
    }

    /**
     * @param $login_on_denied_access
     * @return $this
     */
    public function setLoginOnDeniedAccess($login_on_denied_access)
    {
        $this->login_on_denied_access = $login_on_denied_access;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLogoutThirdPartyLoginToo()
    {
        return $this->logout_third_party_login_too;
    }

    /**
     * @param boolean $logout_third_party_login_too
     */
    public function setLogoutThirdPartyLoginToo($logout_third_party_login_too)
    {
        $this->logout_third_party_login_too = $logout_third_party_login_too;
    }

    public function setTitleMaps($title_maps)
    {
        $this->title_maps = $title_maps;
    }

    public function getTitleMaps()
    {
        return $this->title_maps;

    }

    public function getUserMeta() 
    {
        return $this->user_meta;
    }

    public function setUserMeta($user_meta) 
    {
        $this->user_meta = $user_meta;
        return $this;
    }

    public function getApplicationHash() 
    {
        return $this->application_hash;
    }

    public function setApplicationHash($hash) 
    {
        $this->application_hash = $hash;
        return $this;
    }

    public function setIsAuthenticationWhitelist($whitelist) 
    {
        $this->is_authentication_whitelist = $whitelist;
        return $this;
    }

    public function getIsAuthenticationWhitelist() 
    {
        return $this->is_authentication_whitelist;
    }

    /**
     * @return string
     */
    public function getDefaultAppId() {
        return $this->default_app_id;
    }

    /**
     * @param string $default_app_id
     */
    public function setDefaultAppId($default_app_id) {
        $this->default_app_id = $default_app_id;
    }

    /**
     * @return string
     */
    public function getDefaultAppSecret() {
        return $this->default_app_secret;
    }

    /**
     * @param string $default_app_secret
     */
    public function setDefaultAppSecret($default_app_secret) {
        $this->default_app_secret = $default_app_secret;
    }
}
