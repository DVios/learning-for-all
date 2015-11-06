<?php

require_once '../Constants/DatabaseCredentials.php';

class Authenticator {

    var $dbc;

    public function __construct() {
        
    }

    function signIn($username, $pass) {

        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Failed to connect to MySQL database. " . mysqli_error());
        $query = "SELECT username, password FROM user WHERE username = '$username'";
        $result = mysqli_query($this->dbc, $query) or die("Signing in failed. " . mysqli_error($this->dbc));
        $row = mysqli_fetch_array($result);
        mysqli_close($this->dbc);
        if (empty($row)) {
            return FALSE;
        }
        $hash = $row['password'];
        if (hash_equals($hash, crypt($pass, $hash))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isUserAlreadyExists($username) {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Failed to connect to MySQL database. " . mysqli_error());
        $query = "SELECT username FROM user where username = '$username'";
        $result = mysqli_query($this->dbc, $query) or die("Checking failed. " . mysqli_error($this->dbc));
        $row = mysqli_fetch_array($result);
        mysqli_close($this->dbc);

        if (empty($row)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function signUp($user) {
        $pass = hashPassword($user->getPassword());
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Failed to connect to MySQL database. " . mysqli_error());
        $query = "INSERT INTO `user`(`username`, `password`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `about`) VALUES (" . $user->getUsername() . "," . $pass . "," . $user->getFirst_name() . "," . $user->getMiddle_name() . "," . $user->getLast_name() . "," . $user->getDob() . "," . $user->getGender() . "," . $user->getAbout() . ");";
        $result = mysqli_query($this->dbc, $query) or die("Adding user failed" . mysqli_errno($this->dbc));
        mysqli_close($this->dbc);
        return TRUE;
    }

//    function changePassword($emp_number, $pass_old, $pass_new) {
//
//        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Failed to connect to MySQL database. " . mysqli_error());
//        $query = "SELECT * FROM login where emp_number = '$emp_number'";
//        $result = mysqli_query($this->dbc, $query) or die(mysqli_error($this->dbc));
//        $row = mysqli_fetch_array($result);
//
//        if (empty($row)) {
//
//            return FALSE;
//        } else if (hash_equals($row['password'], crypt($pass_old, $row['password']))) {
//
//            $query = "UPDATE login SET password = '" . $this->hashPassword($pass_new) . "' WHERE emp_number = '$emp_number'";
//            $result = mysqli_query($this->dbc, $query) or die("Change password failed. " . mysqli_error($this->dbc));
//            return TRUE;
//        } else {
//
//            return FALSE;
//        }
//
//        mysqli_close($this->dbc);
//    }


    private function hashPassword($password) {

        /*
         * A higher "cost" is more secure but consumes more processing power
         */
        $cost = 10;
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

        /*
         *  Prefix information about the hash so PHP knows how to verify it later.
         *    "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameters.
         */
        $salt = sprintf("$2a$%02d$", $cost) . $salt;

        /*
         *  Hash the password with the salt using crypt function in PHP.
         *  Only hash value is stored in database.
         */
        $hash = crypt($password, $salt);
        return $hash;
    }

}

?>
