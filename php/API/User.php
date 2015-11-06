<?php

class User {

    private $username;
    private $password;
    private $first_name;
    private $middle_name;
    private $last_name;
    private $dob;
    private $gender;
    private $about;
    private $loginSessionses = array();
    private $logins = array();
    
    function __construct() {
        
    }

        function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getFirst_name() {
        return $this->first_name;
    }

    function getMiddle_name() {
        return $this->middle_name;
    }

    function getLast_name() {
        return $this->last_name;
    }

    function getDob() {
        return $this->dob;
    }

    function getGender() {
        return $this->gender;
    }

    function getAbout() {
        return $this->about;
    }

    function getLoginSessionses() {
        return $this->loginSessionses;
    }

    function getLogins() {
        return $this->logins;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setFirst_name($first_name) {
        $this->first_name = $first_name;
    }

    function setMiddle_name($middle_name) {
        $this->middle_name = $middle_name;
    }

    function setLast_name($last_name) {
        $this->last_name = $last_name;
    }

    function setDob($dob) {
        $this->dob = $dob;
    }

    function setGender($gender) {
        $this->gender = $gender;
    }

    function setAbout($about) {
        $this->about = $about;
    }

    function setLoginSessionses($loginSessionses) {
        $this->loginSessionses = $loginSessionses;
    }

    function setLogins($logins) {
        $this->logins = $logins;
    }


}

?>