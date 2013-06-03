<?php

class DirectAdmin_Installatron {

    private $_host;
    private $_type;
    private $_username;
    private $_password;
    private $_target;
    
    // Application variable
    private $_application   = 'wordpress';
    private $_appLang       = 'en';
    private $_appEmail      = null;
    private $_appUsername   = null;
    private $_appPass       = null;
    private $_appTitle      = null;
    private $_appVersion    = null;
    private $_appContent    = 'no';
    
    // Database variable
    private $_dbType    = 'auto';
    private $_dbHost    = null;
    private $_dbName    = null;
    private $_dbUser    = null;
    private $_dbPass    = null;
    private $_dbPrefix  = null;
    
    /**
     * Generate a password.
     * 
     * @param integer $length The length of the password.
     * @return string The generated password.
     */
    private function generatePassword($length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        $percent = 0;
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $rand = rand(1, 100);
            if ($rand < $percent) {
                $percent = 0;

                $pos = rand(0, strlen($numbers) - 1);
                $pass .= substr($numbers, $pos, 1);
            } else {
                $pos = rand(0, strlen($chars) - 1);
                $pass .= substr($chars, $pos, 1);
            }

            $percent += 20;
        }

        return $pass;
    }
    
    /**
     * Check if an application is a valid application. Change it to wordpress if it isn't.
     * 
     * @param string $application Name of an application.
     * @return string Returns a valid application.
     */
    private function checkApplication($application) {
        switch($application) {
            case 'opencart':
            case 'magento':
            case 'joomla':
                $application = $application;
                break;
            default:
                $application = 'wordpress';
                break;
        }
        
        return $application;
    }

    /**
     * Run a query for Installatron.
     * 
     * @param type $query
     * @return array Response of the query.
     */
    private function query($query) {
        if ($this->_target != '') {
            $query['username'] = $this->_target;
        }
        
        $query['cp-username'] = $this->_username;
        $query['cp-password'] = $this->_password;

        $result = _installatron_call($this->_type, $this->_host, $query, $this->_username, $this->_password, true, "curl");
        if (isset($query['passwd'])) {
            $result['data']['cf-passwd'] = $query['passwd'];
        }

        return $result;
    }

    /**
     * @param string $host The host of the server.
     * @param string $username The username of the server.
     * @param string $password The password of the server.
     * @param string $type The type of the control panel of the server. Valid values: cpanel | plesk | directadmin. Default is directadmin.
     */
    public function __construct($host, $username, $password, $type = 'directadmin') {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require '/usr/local/installatron/lib/helper.automation.php';

        $this->_host        = $host;
        $this->_username    = $username;
        $this->_password    = $password;
        $this->_application = $this->checkApplication($this->_application);

        switch ($type) {
            case 'cpanel':
            case 'plesk':
                $this->_type = $type;
                break;
            default:
                $this->_type = 'directadmin';
                break;
        }
    }
    
    /**
     * Set the application information
     * 
     * @param string $application The name of the application. Examples: wordpress, magento, opencart, joomla, ect. Default is 'wordpress'.
     * @param string $language Set the language of the application. This requires an ISO 639-1 code. Default is 'en'.
     * @param string $email The email address used for the admin of the application and for notifications. Set to null to use default.
     * @param string $username The username for the admin of the application. Set to null to generate an username.
     * @param string $password The password for the admin of the application. Set to null to generate a password.
     * @param string $title The title of the application. Set to null to use default.
     * @param string $version The version of the application. Set to null to install the latest version.
     * @param string $content Set to 'yes' if the application needs basic content, like example products, banners, ect. Default is 'no'.
     */
    public function setApplicationInfo($application = 'wordpress', $language = 'en', $email = null, $username = null, $password = null, $title = null, $version = null, $content = 'no') {
        $this->_application     = $this->checkApplication($application);
        $this->_appLang         = $language;
        $this->_appEmail        = $email;
        $this->_appUsername     = $username;
        if ($password == null)
            $this->_appPass = $this->generatePassword();
        else 
            $this->_appPass         = $password;
        $this->_appTitle        = $title;
        $this->_appVersion      = $version;
        $this->_appContent      = $content;
    }
    
    /**
     * Set the database information for the application.
     * 
     * @param string $type If set to auto, then the database will be created on the application install. Set to manual if the database is already created. Default is auto.
     * @param string $host The host location of the database.
     * @param string $name The name of the database.
     * @param string $user The username of the database.
     * @param string $pass The password of the database.
     * @param string $prefix The prefix for the database tables.
     */
    public function setDatabaseInfo($type = 'auto', $host = null, $name = null, $user = null, $pass = null, $prefix = null) {
        $this->_dbType      = $type;
        $this->_dbHost      = $host;
        $this->_dbName      = $name;
        $this->_dbUser      = $user;
        $this->_dbPass      = $pass;
        $this->_dbPrefix    = $prefix;
    }

    /**
     * Sets the username which will be used for the queries.
     * 
     * @param string $target The target username.
     */
    public function setTarget($target) {
        $this->_target = $target;
    }

    /**
     * Get an list of all installed application.
     * 
     * @return array Response of the query.
     */
    public function applicationList() {
        $query = array(
            'cmd' => 'installs'
        );

        return $this->query($query);
    }
    
    /**
     * Get the details of an application.
     * 
     * @param string $application_id The ID of the application which needs to be edited.
     * @return array Response of the query.
     */
    public function applicationDetails($application_id) {
        $query = array(
            'cmd' => 'view',
            'id' => $application_id
        );

        return $this->query($query);
    }

    /**
     * Install an Installatron application.
     * 
     * @param string $location The location where the application needs to be installed.
     * @return array Response of the query.
     */
    public function applicationInstall($location) {
        // Basic query
        $query = array(
            'cmd' => 'install',
            'application' => $this->_application,
            'url' => $location
        );

        // Application information
        $query['language'] = $this->_appLang;
        if ($this->_appUsername != null) {
            $query['login'] = $this->_appUsername;
        }
        $query['passwd'] = $this->_appPass;
        if ($this->_appEmail != null) {
            $query['email'] = $this->_appEmail;
        }
        if ($this->_appTitle != null) {
            $query['sitetitle'] = $this->_appTitle;
        }
        if ($this->_appVersion != null) {
            $query['version'] = $this->_appVersion;
        }
        $query['content'] = $this->_appContent;
        
        // Database information
        if ($this->_dbType == 'manual') {
            $query['db'] = 'manual';
        } else {
            $query['db'] = 'auto';
        }
        if ($this->_dbHost != null) {
            $query['db-host'] = $this->_dbHost;
        }
        if ($this->_dbName != null) {
            $query['db-name'] = $this->_dbName;
        }
        if ($this->_dbUser != null) {
            $query['db-user'] = $this->_dbUser;
        }
        if ($this->_dbPass != null) {
            $query['db-pass'] = $this->_dbPass;
        }
        if ($this->_dbPrefix != null) {
            $query['db-prefix'] = $this->_dbPrefix;
        }

        // Run query
        return $this->query($query);
    }

    /**
     * Edit an application.
     * 
     * @param string $application_id The ID of the application which needs to be edited.
     * @return array Response of the query.
     */
    public function applicationEdit($application_id) {
        $query = array(
            'cmd' => 'edit',
            'id' => $application_id
        );
        
        // Application information
        $query['language'] = $this->_appLang;
        if ($this->_appUsername != null) {
            $query['login'] = $this->_appUsername;
        }
        $query['passwd'] = $this->_appPass;
        if ($this->_appEmail != null) {
            $query['email'] = $this->_appEmail;
        }
        
        // Database information
        if ($this->_dbHost != null) {
            $query['db-host'] = $this->_dbHost;
        }
        if ($this->_dbName != null) {
            $query['db-name'] = $this->_dbName;
        }
        if ($this->_dbUser != null) {
            $query['db-user'] = $this->_dbUser;
        }
        if ($this->_dbPass != null) {
            $query['db-pass'] = $this->_dbPass;
        }
        if ($this->_dbPrefix != null) {
            $query['db-prefix'] = $this->_dbPrefix;
        }

        // Run query
        return $this->query($query);
    }

    /**
     * Uninstalls an application.
     * 
     * @param string $application_id The ID of the application which needs to be uninstalled.
     * @return array Response of the query.
     */
    public function applicationUninstall($application_id) {
        $query = array(
            'cmd' => 'uninstall',
            'id' => $application_id
        );

        return $this->query($query);
    }
    
    /**
     * Migrate an application.
     * 
     * @param string $source_ftu The FTP URI to the source installed application. For example, "ftp://user:pass@website.com/public_html/wordpress".
     * @param string $source_url The URL to the source installed application. For example, "http://website.com/wordpress".
     * @param string $location The location where the application needs to be installed.
     * @return array Response of the query.
     */
    public function applicationMigrate($source_ftu, $source_url, $location) {
        $query = array(
            'cmd' => 'import',
            'source_ftu' => $source_ftu,
            'source_url' => $source_url,
            'url' => $location,
            'application' => $this->_application
        );

        // Database information
        if ($this->_dbType == 'manual') {
            $query['db'] = 'manual';
        } else {
            $query['db'] = 'auto';
        }
        if ($this->_dbHost != null) {
            $query['db-host'] = $this->_dbHost;
        }
        if ($this->_dbName != null) {
            $query['db-name'] = $this->_dbName;
        }
        if ($this->_dbUser != null) {
            $query['db-user'] = $this->_dbUser;
        }
        if ($this->_dbPass != null) {
            $query['db-pass'] = $this->_dbPass;
        }
        if ($this->_dbPrefix != null) {
            $query['db-prefix'] = $this->_dbPrefix;
        }

        // Run query
        return $this->query($query);
    }

}

?>