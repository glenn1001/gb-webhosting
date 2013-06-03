<?php

class DirectAdmin_User extends DirectAdmin_DirectAdmin{
    
    private $_response;
    
    /**
     * @param string $host The IP address/server name of the DirectAdmin control panel.
     * @param string $username The username of the DirectAdmin control panel.
     * @param string $password The password password of the DirectAdmin control panel.
     * @param integer $port The port of the DirectAdmin control panel.
     */
    function __construct($host, $username, $password, $port = 2222) {
        parent::__construct($host, $username, $password, $port);
    }

    /**
     * Get list of domains.
     * 
     * @return array Response of the query.
     */
    public function domainList() {
        $this->_response = parent::query('CMD_API_SHOW_DOMAINS', $content);
        return $this->_response;
    }

    /**
     * Create a new domain.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param integer $bandwidth The bandwith of a domain. If ubandwith is true, then this will be ignored.
     * @param boolean $ubandwidth Set to true for unlimited bandwidth.
     * @param integer $quota The quota of a domain. If uquota is true, then this will be ignored.
     * @param boolean $uquota Set to true for unlimted quota.
     * @param boolean $ssl Set to true to enable SSL.
     * @param boolean $cgi Set to true to enable CGI.
     * @param boolean $php Set to true to enable PHP.
     * @return array Response of the query.
     */
    public function domainCreate($domain, $bandwidth = 0, $ubandwidth = true, $quota = 0, $uquota = true, $ssl = false, $cgi = true, $php = true) {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'bandwidth' => $bandwidth,
            'quota'     => $quota,
            'ssl'       => ($ssl ? 'ON' : 'OFF'),
            'cgi'       => ($cgi ? 'ON' : 'OFF'),
            'php'       => ($php ? 'ON' : 'OFF')
        );

        if ($ubandwidth) {
            $content['ubandwidth'] = 'unlimited';
        }

        if ($uquota) {
            $content['uquota'] = 'unlimited';
        }

        $this->_response = parent::query('CMD_API_DOMAIN', $content);
        return $this->_response;
    }

    /**
     * Modify a domain.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param integer $bandwidth The bandwith of a domain. If ubandwith is true, then this will be ignored.
     * @param boolean $ubandwidth Set to true for unlimited bandwidth.
     * @param integer $quota The quota of a domain. If uquota is true, then this will be ignored.
     * @param boolean $uquota Set to true for unlimted quota.
     * @param boolean $ssl Set to true to enable SSL.
     * @param boolean $cgi Set to true to enable CGI.
     * @param boolean $php Set to true to enable PHP.
     * @return array Response of the query.
     */
    public function domainModify($domain, $bandwidth = 0, $ubandwidth = true, $quota = 0, $uquota = true, $ssl = false, $cgi = true, $php = true) {
        $content = array(
            'action'    => 'modify',
            'domain'    => $domain,
            'bandwidth' => $bandwidth,
            'quota'     => $quota,
            'ssl'       => ($ssl ? 'ON' : 'OFF'),
            'cgi'       => ($cgi ? 'ON' : 'OFF'),
            'php'       => ($php ? 'ON' : 'OFF')
        );

        if ($ubandwidth) {
            $content['ubandwidth'] = 'unlimited';
        }

        if ($uquota) {
            $content['uquota'] = 'unlimited';
        }

        $this->_response = parent::query('CMD_API_DOMAIN', $content);
        return $this->_response;
    }

    /**
     * Change default domain.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function domainDefault($domain) {
        $content = array(
            'action'    => 'select',
            'default'   => 'default',
            'select0'   => $domain
        );

        $this->_response = parent::query('CMD_API_DOMAIN', $content);
        return $this->_response;
    }

    /**
     * Suspend or unsuspend domains.
     * 
     * @param array $domains Valid domain names in the form: domain.com.
     * @return array Response of the query.
     */
    public function domainSuspend($domains) {
        $content = array(
            'suspend'   => 'suspend',
            'confirmed' => 'yes'
        );

        if (is_array($domains)) {
            $i = 0;
            foreach ($domains as $domain) {
                $content['select' . $i] = $domain;
                $i++;
            }
        }

        $this->_response = parent::query('CMD_API_DOMAIN', $content);
        return $this->_response;
    }

    /**
     * Delete domains.
     * 
     * @param array $domains Valid domain names in the form: domain.com.
     * @return array Response of the query.
     */
    public function domainDelete($domains) {
        $content = array(
            'delete'    => 'delete',
            'confirmed' => 'yes'
        );

        if (is_array($domains)) {
            $i = 0;
            foreach ($domains as $domain) {
                $content['select' . $i] = $domain;
                $i++;
            }
        }

        $this->_response = parent::query('CMD_API_DOMAIN', $content);
        return $this->_response;
    }
    
    /**
     * Update the bandwidth and disk usages of an user. Can only be done once every 10 minutes.
     * 
     * @return array Response of the query.
     */
    public function userStatsUpdate() {
        $content = array(
            'update' => 'update'
        );
        
        // Get current method and change method to POST
        $method = parent::getMethod();
        parent::changeMethod('POST');
        
        // Run query
        $this->_response = parent::query('CMD_API_CHANGE_INFO', $content);
        
        // Change method back
        parent::changeMethod($method);
        
        // Return response
        return $this->_response;
    }
    
    /**
     * Get the stats of an user (bandwidth, disk usage, ect).
     * 
     * @return array Response of the query.
     */
    public function userStats() {
        $content = array();

        $this->_response = parent::query('CMD_API_SHOW_USER_USAGE', $content);
        return $this->_response;
    }
    
    /**
     * Get a list with all FTP accounts.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query. 
     */
    public function ftpList($domain) {
        $content = array(
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_FTP', $content);
        return $this->_response;
    }
    
    /**
     * Create a new FTP account.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The username of the FTP account.
     * @param string $passwd The password of the FTP account.
     * @param string $type The type of the FTP account. Valid values are: domain | ftp | user | custom. Default is domain.
     * @param string $custom_val The root location of the FTP account.
     * @return array Response of the query.
     */
    public function ftpCreate($domain, $user, $passwd, $type = 'domain', $custom_val = '/') {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd
        );
        
        switch($type) {
            case 'custom':
                $content['custom_val'] = $custom_val;
            case 'ftp':
            case 'user':
                $content['type'] = $type;
                break;
            default:
                $content['type'] = 'domain';
                break;
        }

        $this->_response = parent::query('CMD_API_FTP', $content);
        return $this->_response;
    }
    
    /**
     * Modify a FTP account.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The username of the FTP account.
     * @param string $passwd The password of the FTP account.
     * @param string $type The type of the FTP account. Valid values are: domain | ftp | user | custom. Default is domain.
     * @param string $custom_val The root location of the FTP account.
     * @return array Response of the query.
     */
    public function ftpModify($domain, $user, $passwd, $type = 'domain', $custom_val = '/') {
        $content = array(
            'action'    => 'modify',
            'domain'    => $domain,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd
        );
        
        switch($type) {
            case 'custom':
                $content['custom_val'] = $custom_val;
            case 'ftp':
            case 'user':
                $content['type'] = $type;
                break;
            default:
                $content['type'] = 'domain';
                break;
        }

        $this->_response = parent::query('CMD_API_FTP', $content);
        return $this->_response;
    }
    
    /**
     * Suspend FTP account(s).
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $users The user or users which need to be suspended.
     * @return array Response of the query.
     */
    public function ftpSuspend($domain, $users) {
        $content = array(
            'action'    => 'delete',
            'suspend'   => 'suspend',
            'domain'    => $domain
        );

        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_FTP', $content);
        return $this->_response;
    }
    
    /**
     * Unsuspend FTP account(s).
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $users The user or users which need to be unsuspended.
     * @return array Response of the query.
     */
    public function ftpUnsuspend($domain, $users) {
        $content = array(
            'action'    => 'delete',
            'unsuspend' => 'unsuspend',
            'domain'    => $domain
        );

        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_FTP', $content);
        return $this->_response;
    }
    
    /**
     * Delete FTP account(s).
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $users The user or users which need to be deleted.
     * @return array Response of the query.
     */
    public function ftpDelete($domain, $users) {
        $content = array(
            'action' => 'delete',
            'domain' => $domain
        );

        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_FTP', $content);
        return $this->_response;
    }
    
    /**
     * Get list of subdomains.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function subdomainList($domain) {
        $content = array(
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_SUBDOMAINS', $content);
        return $this->_response;
    }
    
    /**
     * Create a new subdomain.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $subdomain Name of the subdomain.
     * @return array Response of the query.
     */
    public function subdomainCreate($domain, $subdomain) {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'subdomain' => $subdomain
        );

        $this->_response = parent::query('CMD_API_SUBDOMAINS', $content);
        return $this->_response;
    }
    
    /**
     * Delete subdomain(s).
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $subdomains Subdomain(s) which need to be deleted.
     * @param boolean $contents Set to false if the subdomain folder doesn't need to be deleted.
     * @return array Response of the query.
     */
    public function subdomainDelete($domain, $subdomains, $contents = true) {
        $content = array(
            'action' => 'delete',
            'domain' => $domain
        );
        
        if (is_array($subdomains)) {
            $i = 0;
            foreach($subdomains as $subdomain) {
                $content['select' . $i] = $subdomain;
                $i++;
            }
        } else {
            $content['select0'] = $subdomains;
        }
        
        if ($contents) {
            $content['contents'] = 'yes';
        } else {
            $content['contents'] = 'no';
        }

        $this->_response = parent::query('CMD_API_SUBDOMAINS', $content);
        return $this->_response;
    }
    
    /**
     * Get list of databases.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function databaseList($domain) {
        $content = array(
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_DATABASES', $content);
        return $this->_response;
    }
    
    /**
     * Create a new database.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $name Name of the database.
     * @param string $user Username of the database user.
     * @param string $passwd Password of the database user.
     * @param string $userlist Select an existing database user. Default is NULL (creates a new database user).
     * @return array Response of the query.
     */
    public function databaseCreate($domain, $name, $user, $passwd, $userlist = NULL) {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'name'      => $name,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd
        );
        
        if ($userlist != NULL) {
            $content['userlist'] = $userlist;
        }

        $this->_response = parent::query('CMD_API_DATABASES', $content);
        return $this->_response;
    }
    
    /**
     * Delete database(s).
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $databases The database(s) which need to be deleted.
     * @return array Response of the query.
     */
    public function databaseDelete($domain, $databases) {
        $content = array(
            'action' => 'delete',
            'domain' => $domain
        );
        
        if (is_array($databases)) {
            $i = 0;
            foreach($databases as $database) {
                $content['select' . $i] = $database;
                $i++;
            }
        } else {
            $content['select0'] = $databases;
        }
        
        $this->_response = parent::query('CMD_API_DATABASES', $content);
        return $this->_response;
    }
    
    /**
     * Get a list of users of a database.
     * 
     * @param string $database Name of the database.
     * @return array Response of the query.
     */
    public function databaseUserList($database) {
        $content = array(
            'name' => $database
        );

        $this->_response = parent::query('CMD_API_DB_USER', $content);
        return $this->_response;
    }
    
    /**
     * Create a new user for a database.
     * 
     * @param string $database Name of the database.
     * @param string $user Username of the user of the database.
     * @param string $passwd Password of the user of the database.
     * @return array Response of the query.
     */
    public function databaseUserCreate($database, $user, $passwd) {
        $content = array(
            'action'    => 'create',
            'name'      => $database,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd
        );

        $this->_response = parent::query('CMD_API_DB_USER', $content);
        return $this->_response;
    }
    
    /**
     * Modify user for a database.
     * 
     * @param string $database Name of the database.
     * @param string $user Username of the user of the database.
     * @param string $passwd Password of the user of the database.
     * @return array Response of the query.
     */
    public function databaseUserModify($database, $user, $passwd) {
        $content = array(
            'action'    => 'modify',
            'name'      => $database,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd
        );

        $this->_response = parent::query('CMD_API_DB_USER', $content);
        return $this->_response;
    }
    
    /**
     * Delete user(s) of a database.
     * 
     * @param string $database Name of the database.
     * @param string/array $users The user(s) which need to be deleted from the database.
     * @return array Response of the query.
     */
    public function databaseUserDelete($database, $users) {
        $content = array(
            'action'    => 'delete',
            'name'      => $database
        );
        
        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_DB_USER', $content);
        return $this->_response;
    }

    /**
     * Get list of used email addresses.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function emailAccountList($domain) {
        $content = array(
            'action' => 'list',
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_POP', $content);
        return $this->_response;
    }

    /**
     * Create a new email address.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $username Username for the email address.
     * @param string $passwd Password for the email address.
     * @param integer $quota Email quota in MB. Default is 50.
     * @return array Response of the query.
     */
    public function emailAccountCreate($domain, $user, $passwd, $quota = 50) {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd,
            'quota'     => $quota
        );

        $this->_response = parent::query('CMD_API_POP', $content);
        return $this->_response;
    }

    /**
     * Modify a email address.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $username Username for the email address.
     * @param string $passwd Password for the email address.
     * @param integer $quota Email quota in MB. Default is 50.
     * @return array Response of the query.
     */
    public function emailAccountModify($domain, $user, $passwd, $quota = 50) {
        $content = array(
            'action'    => 'modify',
            'domain'    => $domain,
            'user'      => $user,
            'passwd'    => $passwd,
            'passwd2'   => $passwd,
            'quota'     => $quota
        );

        $this->_response = parent::query('CMD_API_POP', $content);
        return $this->_response;
    }

    /**
     * Suspend an email address.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $username Username for the email address.
     * @return array Response of the query.
     */
    public function emailAccountSuspend($domain, $user) {
        $content = array(
            'action'    => 'delete',
            'suspend'   => 'suspend',
            'domain'    => $domain,
            'user'      => $user
        );

        $this->_response = parent::query('CMD_API_POP', $content);
        return $this->_response;
    }

    /**
     * Unsuspend an email address.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $username Username for the email address.
     * @return array Response of the query.
     */
    public function emailAccountUnsuspend($domain, $user) {
        $content = array(
            'action'    => 'delete',
            'unsuspend' => 'unsuspend',
            'domain'    => $domain,
            'user'      => $user
        );

        $this->_response = parent::query('CMD_API_POP', $content);
        return $this->_response;
    }

    /**
     * Delete an email address.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $username Username for the email address.
     * @return array Response of the query.
     */
    public function emailAccountDelete($domain, $user) {
        $content = array(
            'action'    => 'delete',
            'domain'    => $domain,
            'user'      => $user
        );

        $this->_response = parent::query('CMD_API_POP', $content);
        return $this->_response;
    }
    
    /**
     * Get the catch all settings.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function emailCatchAllSettings($domain) {
        $content = array(
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_EMAIL_CATCH_ALL', $content);
        return $this->_response;
    }
    
    /**
     * Edit the catch all settings.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $catch The type of the catch. Examples 'fail', 'blackhole', 'address'. Default is 'fail'.
     * @param string $value Only required when catch is address. All emails will be forwarded to this address.
     * @return array Response of the query.
     */
    public function emailCatchAllEdit($domain, $catch = 'fail', $value = '') {
        switch ($catch) {
            case 'blackhole':
                $catch = ':blackhole:';
                break;
            case 'address':
                $catch = 'address';
                break;
            default:
                $catch = ':fail:';
                break;
        }
        
        $content = array(
            'update'    => 'Update',
            'domain'    => $domain,
            'catch'     => $catch,
            'value'     => $value
        );

        $this->_response = parent::query('CMD_API_EMAIL_CATCH_ALL', $content);
        return $this->_response;
    }

    /**
     * List of email forwarders for this domain.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function emailForwarderList($domain) {
        $content = array(
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_EMAIL_FORWARDERS', $content);
        return $this->_response;
    }
    
    /**
     * Add a forwarder.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user from where emails must be forwarded.
     * @param string $email Where the emails need to be forwarded to.
     * @return array Response of the query.
     */
    public function emailForwarderCreate($domain, $user, $email) {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'user'      => $user,
            'email'     => $email
        );

        $this->_response = parent::query('CMD_API_EMAIL_FORWARDERS', $content);
        return $this->_response;
    }
    
    /**
     * Modify a forwarder.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user from where emails must be forwarded.
     * @param string $email Where the emails need to be forwarded to.
     * @return array Response of the query.
     */
    public function emailForwarderModify($domain, $user, $email) {
        $content = array(
            'action'    => 'modify',
            'domain'    => $domain,
            'user'      => $user,
            'email'     => $email
        );

        $this->_response = parent::query('CMD_API_EMAIL_FORWARDERS', $content);
        return $this->_response;
    }
    
    /**
     * Delete forwarder(s)
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $users The user or users which need to be deleted.
     * @return array Response of the query.
     */
    public function emailForwarderDelete($domain, $users) {
        $content = array(
            'action'    => 'delete',
            'domain'    => $domain
        );
        
        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_EMAIL_FORWARDERS', $content);
        return $this->_response;
    }
    
    /**
     * Get a list with all autoresponders.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function emailAutoresponderList($domain) {
        $content = array(
            'domain' => $domain
        );

        $this->_response = parent::query('CMD_API_EMAIL_AUTORESPONDER', $content);
        return $this->_response;
    }
    
    /**
     * Get details of autoresponder.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user of the autoresponder.
     * @return array Response of the query.
     */
    public function emailAutoresponderDetails($domain, $user) {
        $content = array(
            'domain'    => $domain,
            'user'      => $user
        );

        $this->_response = parent::query('CMD_API_EMAIL_AUTORESPONDER_MODIFY', $content);
        return $this->_response;
    }
    
    /**
     * Create a autoresponder.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user of the autoresponder.
     * @param string $text The message of the autoresponder.
     * @param boolean $cc Set to true if the email must be send as 'CC' to another email address.
     * @param type $email Email address which will be emailed as 'CC'. Seperate multiple email address with a comma ','.
     * @return array Response of the query.
     */
    public function emailAutoresponderCreate($domain, $user, $text, $cc = false, $email = '') {
        $content = array(
            'action'    => 'create',
            'domain'    => $domain,
            'user'      => $user,
            'text'      => $text,
            'email'     => $email
        );
        
        if ($cc)
            $content['cc'] = 'ON';
        else
            $content['cc'] = 'OFF';

        $this->_response = parent::query('CMD_API_EMAIL_AUTORESPONDER', $content);
        return $this->_response;
    }
    
    /**
     * Modify a autoresponder.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user of the autoresponder.
     * @param string $text The message of the autoresponder.
     * @param boolean $cc Set to true if the email must be send as 'CC' to another email address.
     * @param type $email Email address which will be emailed as 'CC'. Seperate multiple email address with a comma ','.
     * @return array Response of the query.
     */
    public function emailAutoresponderModify($domain, $user, $text, $cc = false, $email = '') {
        $content = array(
            'action'    => 'modify',
            'domain'    => $domain,
            'user'      => $user,
            'text'      => $text,
            'email'     => $email
        );
        
        if ($cc)
            $content['cc'] = 'ON';
        else
            $content['cc'] = 'OFF';

        $this->_response = parent::query('CMD_API_EMAIL_AUTORESPONDER', $content);
        return $this->_response;
    }
    
    /**
     * Delete autoresponder(s)
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $users The user or users which need to be deleted.
     * @return array Response of the query.
     */
    public function emailAutoresponderDelete($domain, $users) {
        $content = array(
            'action'    => 'delete',
            'domain'    => $domain
        );
        
        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_EMAIL_AUTORESPONDER', $content);
        return $this->_response;
    }
    
    /**
     * Get a list with all vacation messages.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @return array Response of the query.
     */
    public function emailVacationList($domain) {
        $content = array(
            'domain' => $domain
        );

        $response = parent::query('CMD_API_EMAIL_VACATION', $content);
        
        unset($this->_response);
        $this->_response = array();
        
        // Parse response
        $i = 0;
        foreach ($response as $k => $v) {
            $tmpArray = array('user' => $k);
            
            $data = explode('&', $v);
            foreach ($data as $value) {
                $array = explode('=', $value);
                $tmpArray[urldecode($array[0])] = urldecode($array[1]);
            }
            $this->_response[] = $tmpArray;
            $i++;
        }
        
        // Return parsed response
        return $this->_response;
    }
    
    /**
     * Get details of vacation message.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user of the vacation message.
     * @return array Response of the query.
     */
    public function emailVacationDetails($domain, $user) {
        $content = array(
            'domain'    => $domain,
            'user'      => $user
        );

        $this->_response = parent::query('CMD_API_EMAIL_VACATION_MODIFY', $content);
        
        return $this->_response;
    }
    
    /**
     * Create a vacation message.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user of the vacation message.
     * @param string $text The message which have to be send.
     * @param integer $startday The startday of the vacation message.
     * @param integer $startmonth The startmonth of the vacation message.
     * @param integer $startyear The startyear of the vacation message.
     * @param integer $endday The endday of the vacation message.
     * @param integer $endmonth The endmonth of the vacation message.
     * @param integer $endyear The endyear of the vacation message.
     * @param string $starttime The starttime of the vacation message. Examples: 'morning', 'afternoon', 'evening'. Default is 'morning'.
     * @param string $endtime The endtime of the vacation message. Examples: 'morning', 'afternoon', 'evening'. Default is 'evening'.
     * @return array Response of the query.
     */
    public function emailVacationCreate($domain, $user, $text, $startday, $startmonth, $startyear, $endday, $endmonth, $endyear, $starttime = 'morning', $endtime = 'evening') {
        // Validate starttime
        switch ($starttime) {
            case 'afternoon':
            case 'evening':
                $starttime = $starttime;
                break;
            default:
                $starttime = 'morning';
                break;
        }
        
        // Validate endtime
        switch ($endtime) {
            case 'morning':
            case 'evening':
                $endtime = $endtime;
                break;
            default:
                $endtime = 'afternoon';
                break;
        }
        
        // Validate startday
        if ($startday < 1)
            $startday = 1;
        if ($startday > 31)
            $startday = 31;
        
        // Validate endday
        if ($endday < 1)
            $endday = 1;
        if ($endday > 31)
            $endday = 31;
        
        // Validate startmonth
        if ($startmonth < 1)
            $startmonth = 1;
        if ($startmonth > 12)
            $startmonth = 12;
        
        // Validate endmonth
        if ($endmonth < 1)
            $endmonth = 1;
        if ($endmonth > 12)
            $endmonth = 12;
        
        $content = array(
            'action'        => 'create',
            'domain'        => $domain,
            'user'          => $user,
            'text'          => $text,
            'starttime'     => $starttime,
            'startday'      => $startday,
            'startmonth'    => $startmonth,
            'startyear'     => $startyear,
            'endtime'       => $endtime,
            'endday'        => $endday,
            'endmonth'      => $endmonth,
            'endyear'       => $endyear
        );
        
        $this->_response = parent::query('CMD_API_EMAIL_VACATION', $content);
        
        return $this->_response;
    }
    
    /**
     * Modify a vacation message.
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string $user The user of the vacation message.
     * @param string $text The message which have to be send.
     * @param integer $startday The startday of the vacation message.
     * @param integer $startmonth The startmonth of the vacation message.
     * @param integer $startyear The startyear of the vacation message.
     * @param integer $endday The endday of the vacation message.
     * @param integer $endmonth The endmonth of the vacation message.
     * @param integer $endyear The endyear of the vacation message.
     * @param string $starttime The starttime of the vacation message. Examples: 'morning', 'afternoon', 'evening'. Default is 'morning'.
     * @param string $endtime The endtime of the vacation message. Examples: 'morning', 'afternoon', 'evening'. Default is 'evening'.
     * @return array Response of the query.
     */
    public function emailVacationModify($domain, $user, $text, $startday, $startmonth, $startyear, $endday, $endmonth, $endyear, $starttime = 'morning', $endtime = 'evening') {
        // Validate starttime
        switch ($starttime) {
            case 'afternoon':
            case 'evening':
                $starttime = $starttime;
                break;
            default:
                $starttime = 'morning';
                break;
        }
        
        // Validate endtime
        switch ($endtime) {
            case 'morning':
            case 'evening':
                $endtime = $endtime;
                break;
            default:
                $endtime = 'afternoon';
                break;
        }
        
        // Validate startday
        if ($startday < 1)
            $startday = 1;
        if ($startday > 31)
            $startday = 31;
        
        // Validate endday
        if ($endday < 1)
            $endday = 1;
        if ($endday > 31)
            $endday = 31;
        
        // Validate startmonth
        if ($startmonth < 1)
            $startmonth = 1;
        if ($startmonth > 12)
            $startmonth = 12;
        
        // Validate endmonth
        if ($endmonth < 1)
            $endmonth = 1;
        if ($endmonth > 12)
            $endmonth = 12;
        
        $content = array(
            'action'        => 'modify',
            'domain'        => $domain,
            'user'          => $user,
            'text'          => $text,
            'starttime'     => $starttime,
            'startday'      => $startday,
            'startmonth'    => $startmonth,
            'startyear'     => $startyear,
            'endtime'       => $endtime,
            'endday'        => $endday,
            'endmonth'      => $endmonth,
            'endyear'       => $endyear
        );
        
        $this->_response = parent::query('CMD_API_EMAIL_VACATION', $content);
        
        return $this->_response;
    }
    
    /**
     * Delete vacation message(s)
     * 
     * @param string $domain A valid domain name in the form: domain.com.
     * @param string/array $users The user or users which need to be deleted.
     * @return array Response of the query.
     */
    public function emailVacationDelete($domain, $users) {
        $content = array(
            'action'    => 'delete',
            'domain'    => $domain
        );
        
        if (is_array($users)) {
            $i = 0;
            foreach($users as $user) {
                $content['select' . $i] = $user;
                $i++;
            }
        } else {
            $content['select0'] = $users;
        }

        $this->_response = parent::query('CMD_API_EMAIL_VACATION', $content);
        return $this->_response;
    }
    
    public function emailMailingList() {
        
    }
    
    /**
     * Get a list with all cron jobs.
     * 
     * @return array Response of the query.
     */
    public function cronjobList() {
        $response = parent::query('CMD_API_CRON_JOBS');
        
        unset($this->_response);
        $this->_response = array();
        
        // Parse response
        foreach ($response as $key => $value) {
            $data = explode(' ', $value);
            
            if ($key !== 'MAILTO') {
                $this->_response['cronjobs'][] = array(
                    'id'            => $key,
                    'minute'        => $data[0],
                    'hour'          => $data[1],
                    'dayofmonth'    => $data[2],
                    'month'         => $data[3],
                    'dayofweek'     => $data[4],
                    'command'       => $data[5]
                );
            } else {
                $this->_response['mailto'] = array(
                    'email'         => $data[0]
                );
            }
        }
        
        return $this->_response;
    }
    
    /**
     * Create a cronjob
     * 
     * @param string $command The command of the cronjob.
     * @param string $minute The minute of the cronjob. Default is '*'.
     * @param string $hour The hour of the cronjob. Default is '*'.
     * @param string $dayofmonth The 'day of month' of the cronjob. Default is '*'.
     * @param string $month The month of the cronjob. Default is '*'.
     * @param string $dayofweek The 'day of week' of the cronjob. Default is '*'.
     * @return array Response of the query.
     */
    public function cronjobCreate($command, $minute = '*', $hour = '*', $dayofmonth = '*', $month = '*', $dayofweek = '*') {
        $content = array(
            'action'        => 'create',
            'minute'        => $minute,
            'hour'          => $hour,
            'dayofmonth'    => $dayofmonth,
            'month'         => $month,
            'dayofweek'     => $dayofweek,
            'command'       => $command
        );
        
        $this->_response = parent::query('CMD_API_CRON_JOBS', $content);
        return $this->_response;
    }
    
    /**
     * Modify a cronjob
     * 
     * @param integer $cronjobid The ID of the cronjob.
     * @param string $command The command of the cronjob.
     * @param string $minute The minute of the cronjob. Default is '*'.
     * @param string $hour The hour of the cronjob. Default is '*'.
     * @param string $dayofmonth The 'day of month' of the cronjob. Default is '*'.
     * @param string $month The month of the cronjob. Default is '*'.
     * @param string $dayofweek The 'day of week' of the cronjob. Default is '*'.
     * @return array Response of the query.
     */
    public function cronjobModify($cronjobid, $command, $minute = '*', $hour = '*', $dayofmonth = '*', $month = '*', $dayofweek = '*') {
        $content = array(
            'id'            => $cronjobid,
            'action'        => 'modify',
            'save'          => 'Save',
            'minute'        => $minute,
            'hour'          => $hour,
            'dayofmonth'    => $dayofmonth,
            'month'         => $month,
            'dayofweek'     => $dayofweek,
            'command'       => $command
        );
        
        $this->_response = parent::query('CMD_API_CRON_JOBS', $content);
        return $this->_response;
    }
    
    /**
     * Modify the email address for cronjobs.
     * 
     * @param string $email The email address for the responses of cronjobs. Default is ''.
     * @return array Response of the query.
     */
    public function cronjobEmail($email = '') {
        $content = array(
            'action'    => 'saveemail',
            'email'     => $email
        );
        
        $this->_response = parent::query('CMD_API_CRON_JOBS', $content);
        return $this->_response;
    }
    
    /**
     * Delete cronjob(s)
     * 
     * @param string/array $cronIds The cronjob ID or cronjob ID's which need to be deleted.
     * @return array Response of the query.
     */
    public function cronjobDelete($cronIds) {
        $content = array(
            'action' => 'delete'
        );
        
        if (is_array($cronIds)) {
            $i = 0;
            foreach($cronIds as $cronId) {
                $content['select' . $i] = $cronId;
                $i++;
            }
        } else {
            $content['select0'] = $cronIds;
        }

        $this->_response = parent::query('CMD_API_CRON_JOBS', $content);
        return $this->_response;
    }
    
    public function siteRedirectList() {
        
    }
    
    public function domainPointerList() {
        
    }

}

?>