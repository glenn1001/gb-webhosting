<?php

abstract class DirectAdmin_DirectAdmin {

    private $_sock;
    
    private $_host;
    private $_username;
    private $_password;
    private $_port;
    
    private $_response;
    
    private $_method = 'GET';
    
    /**
     * @param string $host The IP address/server name of the DirectAdmin control panel.
     * @param string $username The username of the DirectAdmin control panel.
     * @param string $password The password password of the DirectAdmin control panel.
     * @param integer $port The port of the DirectAdmin control panel.
     */
    public function __construct($host, $username, $password, $port = 2222) {
        $this->_host = $host;
        $this->_username = $username;
        $this->_password = $password;
        $this->_port = $port;

        $this->_sock = new DirectAdmin_HTTPSocket();
        $this->connect();
    }

    /**
     * Change login.
     * 
     * @param string $username The username of the DirectAdmin control panel.
     * @param string $password The password password of the DirectAdmin control panel.
     */
    public function changeLogin($username, $password) {
        $this->_username = $username;
        $this->_password = $password;
        $this->login();
    }
    
    /**
     * Change the method.
     * 
     * @param string $method The method of the requests (POST, GET or HEAD).
     */
    protected function changeMethod($method = 'GET') {
        $method = strtoupper($method);
        
        if ($method != 'POST' && $method != 'HEAD') {
            $method = 'GET';
        }
        
        $this->_method = $method;

        $this->_sock->set_method($method);
    }
    
    /**
     * Get the current method.
     * 
     * @return string The current method of all queries. 
     */
    protected function getMethod() {
        return $this->_method;
    }

    /**
     * Run a query for the DirectAdmin control panel.
     * 
     * @param string $request 
     * @param array $content An array with the content for a query.
     * @return array Returns an array with the result of an DirectAdmin query.
     */
    protected function query($request, $content) {
        $request = strtoupper($request);
        
        $this->_sock->query('/' . $request, $content);
        $this->parseResult();
        return $this->_response;
    }

    /**
     * Connect to the DirectAdmin control panel.
     */
    private function connect() {
        $this->_sock->connect($this->_host, $this->_port);
        $this->login();
    }

    /**
     * Set login for the DirectAdmin control panel.
     */
    private function login() {
        $this->_sock->set_login($this->_username, $this->_password);
    }
    
    /**
     * Parse the result of the DirectAdmin query.
     * 
     * @param type $result
     * @return array Peturns an array with the parsed result of an DirectAdmin query.
     */
    private function parseResult() {
        $this->_response = $this->_sock->fetch_parsed_body();
    }

}