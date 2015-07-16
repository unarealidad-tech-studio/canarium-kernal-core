<?php

namespace CanariumCore\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $site_name = 'Canarium Instance Startup Page';

    protected $verbose_title = false;

    protected $is_authentication_required = false;

    protected $login_on_denied_access = true;

    protected $title_maps = array();

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

    public function setTitleMaps($title_maps)
    {
        $this->title_maps = $title_maps;
    }

    public function getTitleMaps()
    {
        return $this->title_maps;
    }

}
