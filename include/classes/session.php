<?php

include("database.php");
include("mailer.php");
include("form.php");

class Session
{
   var $username;
   var $userid;
   var $userlevel;
   var $time;
   var $logged_in;
   var $userinfo = array();
   var $url;
   var $referrer;

   function __construct()
   {
      date_default_timezone_set('Asia/Karachi');
      $this->time = time();
      $this->startSession();
   }


   function startSession()
   {
      global $database;
      session_start();


      $this->logged_in = $this->checkLogin();


      if (!$this->logged_in) {
         $this->username = $_SESSION['username'] = GUEST_NAME;
         $this->userlevel = GUEST_LEVEL;
         $remote_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
         $database->addActiveGuest($remote_ip, $this->time);
      } else {
         $database->addActiveUser($this->username, $this->time);
      }


      $database->removeInactiveUsers();
      $database->removeInactiveGuests();


      if (isset($_SESSION['url'])) {
         $this->referrer = $_SESSION['url'];
      } else {
         $this->referrer = "/";
      }


      $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
   }


   function checkLogin()
   {
      global $database;

      $current_time = time();

      if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
         $_SESSION['username'] = $_COOKIE['cookname'];
         $_SESSION['userid'] = $_COOKIE['cookid'];
         $_SESSION['login_time'] = $_SESSION['login_time'] ?? $current_time;
      }

      if (
         isset($_SESSION['username']) &&
         isset($_SESSION['userid']) &&
         $_SESSION['username'] != GUEST_NAME
      ) {
         if (!isset($_SESSION['login_time']) || ($current_time - $_SESSION['login_time']) > COOKIE_EXPIRE) {
            $this->logout();
            return false;
         }

         if ($database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0) {
            $this->logout();
            return false;
         }

         $this->userinfo = $database->getUserInfo($_SESSION['username']);
         $this->username = $this->userinfo['username'];
         $this->userid = $this->userinfo['userid'];
         $this->userlevel = $this->userinfo['userlevel'];
         return true;
      }

      $this->logout();
      return false;
   }


   function login($subuser, $subpass)
   {
      global $database, $form;

      // Basic validation
      if (!$subuser || trim($subuser) === '') {
         $form->setError("user", "* Registration number not entered");
      }
      if (!$subpass) {
         $form->setError("pass", "* Password not entered");
      }
      if ($form->num_errors > 0) {
         return false;
      }

      // Confirm password using registration number
      $result = $database->confirmUserPass($subuser, $subpass);

      if ($result == 1) {
         $form->setError("user", "* Username not found");
         return false;
      }
      if ($result == 2) {
         $form->setError("pass", "* Invalid password");
         return false;
      }


      // $result = $database->getinactiveusers($subuser);


      // if ($result == 2) {
      //    $form->setError("user", "* Your account is inactive. Contact admin.");
      //    return false;
      // }


      // 🔑 IMPORTANT: Get full user using registration_no
      $resultforsession = $database->loginsession($subuser);
      if (!$resultforsession) {
         $form->setError("user", "* Invalid registration number");
         return false;
      }
      $subuser = $resultforsession['username'];


      // Load user info using REAL username
      $this->userinfo = $database->getUserInfo($subuser);
      $this->username = $_SESSION['username'] = $this->userinfo['username'];
      $this->userid = $_SESSION['userid'] = $this->generateRandID();
      $this->userlevel = $this->userinfo['userlevel'];

      $database->updateUserField($this->username, "userid", $this->userid);
      $database->addActiveUser($this->username, $this->time);
      $remote_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
      $database->removeActiveGuest($remote_ip);

      // ⏱ Start inactivity timer
      $_SESSION['login_time'] = time();

      // 🍪 Remember cookies
      setcookie("cookname", $this->username, time() + COOKIE_EXPIRE, COOKIE_PATH);
      setcookie("cookid", $this->userid, time() + COOKIE_EXPIRE, COOKIE_PATH);

      return true;
   }


   function logout()
   {
      global $database;
      if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])) {
         setcookie("cookname", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
         setcookie("cookid", "", time() - COOKIE_EXPIRE, COOKIE_PATH);
      }


      unset($_SESSION['username']);
      unset($_SESSION['userid']);


      $this->logged_in = false;


      $database->removeActiveUser($this->username);
      $remote_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
      $database->addActiveGuest($remote_ip, $this->time);


      $this->username = GUEST_NAME;
      $this->userlevel = GUEST_LEVEL;
   }

   ////////// Start Custom Functions
   function register($subuser, $subpass, $subemail)
   {
      global $database, $form, $mailer;


      $field = "user";
      if (empty($subuser)) {
         $form->setError($field, "*user not entered");
      } else {
         if ($database->usernameTaken($subuser)) {
            $form->setError($field, "* Username already in use");
         }
      }


      $field = "pass";
      if (!$subpass) {
         $form->setError($field, "* Password not entered");
      }
      // $field = "cpass"; 
      // if(!$subpass){
      //    $form->setError($field, "* Confirm password not entered");
      // }
      // $field = "pass"; 
      // if($subpass != $subcpass){
      //    $form->setError($field, "* Password does not match");
      // }

      $field = "email";
      if (!$subemail || strlen($subemail = trim($subemail)) == 0) {
         $form->setError($field, "* Email not entered");
      }
      if ($form->num_errors > 0) {
         return 1;
      } else {
         if ($database->addNewUser($subuser, $subpass, $subemail)) {

            return 0;
         } else {
            return 2;
         }
      }
   }


   function addclass($name, $description, $slots)
   {
      global $database, $form, $mailer;


      $field = "name";
      if (empty($name)) {
         $form->setError($field, "*name not entered");
      } else {
         if ($database->classnameTaken($name)) {
            $form->setError($field, "* class name already in use");
         }
      }


      $field = "slots";
      if (!$slots) {
         $form->setError($field, "* slots not entered");
      }


      if ($form->num_errors > 0) {
         return 1;
      } else {
         if ($database->addclass($name, $description, $slots)) {

            return 0;
         } else {
            return 2;
         }
      }
   }

   function uploadimage($username, $image)
   {
      global $database, $form;




      if ($database->uploadimage($username, $image)) {
         return 0;
      } else {
         return 2;
      }


   }

   function change_accountdetails($username, $displayname, $email, $phone)
   {
      global $database, $form;




      if ($database->change_accountdetails($username, $displayname, $email, $phone)) {
         return 0;
      } else {
         return 2;
      }


   }

   function changepassword($username, $curpass, $newpass)
   {
      global $database, $form;

      // 1. Check if current password was entered
      if (empty($curpass)) {
         $form->setError("curpass", "* Current password is required");
         return 1;  // stop here
      }

      // 2. Verify current password
      $result = $database->confirmUserPass($username, $curpass);

      if ($result == 1) {
         $form->setError("user", "* User not found");
         return 1;
      } else if ($result == 2) {
         // wrong password
         $form->setError("curpass", "* Invalid current password");
         return 1;   // STOP → DO NOT CHANGE PASSWORD
      }

      // 3. If valid, update password
      if ($database->changepassword($username, $curpass, $newpass)) {
         return 0;  // success
      } else {
         return 2;  // db problem
      }
   }











   function editAccount($subcurpass, $subnewpass, $subemail)
   {
      global $database, $form;

      // $jumo2 = md5($_SESSION['CSRF_Code'] . '8j5j&*&K5jrffgF9wAJDIH' . 'JKHds998954(*)(*dfkjll');

      // $field = "CSRF_Code";
      // if ($CSRF_Code != $jumo2) {
      //    $form->setError($field, "<br>Network error. please try again.");
      // }

      if ($subnewpass) {

         $field = "curpass";
         if (!$subcurpass) {
            $form->setError($field, "* Current Password not entered");
         } else {

            $subcurpass = stripslashes($subcurpass);
            if (
               strlen($subcurpass) < 4 ||
               !preg_match("/^([0-9a-z])+$/", ($subcurpass = trim($subcurpass)))
            ) {
               $form->setError($field, "* Current Password incorrect");
            }

            if ($database->confirmUserPass($this->username, md5($subcurpass)) != 0) {
               $form->setError($field, "* Current Password incorrect");
            }
         }


         $field = "newpass";

         $subpass = stripslashes($subnewpass);
         if (strlen($subnewpass) < 4) {
            $form->setError($field, "* New Password too short");
         } else if (!preg_match("/^([0-9a-z])+$/", ($subnewpass = trim($subnewpass)))) {
            $form->setError($field, "* New Password not alphanumeric");
         }
      } else if ($subcurpass) {

         $field = "newpass";
         $form->setError($field, "* New Password not entered");
      }


      $field = "email";
      if ($subemail && strlen($subemail = trim($subemail)) > 0) {

         $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
            . "@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
            . "\.([a-z]{2,}){1}$/";
         if (!preg_match($regex, $subemail)) {
            $form->setError($field, "* Email invalid");
         }
         $subemail = stripslashes($subemail);
      }


      if ($form->num_errors > 0) {
         return false;
      }


      if ($subcurpass && $subnewpass) {
         $database->updateUserField($this->username, "password", md5($subnewpass));
      }


      if ($subemail) {
         $database->updateUserField($this->username, "email", $subemail);
      }


      return true;
   }











   ////////////////////////// END Custom Functions

   function isAdmin()
   {
      return ($this->userlevel == ADMIN_LEVEL ||
         $this->username == ADMIN_NAME);
   }

   function isMaster()
   {
      return ($this->userlevel == MASTER_LEVEL);
   }

   function isAgent()
   {
      return ($this->userlevel == AGENT_LEVEL);
   }

   function isMember()
   {
      return ($this->userlevel == AGENT_MEMBER_LEVEL);
   }



   function generateRandID()
   {
      return md5($this->generateRandStr(16));
   }


   function generateRandStr($length)
   {
      $randstr = "";
      for ($i = 0; $i < $length; $i++) {
         $randnum = mt_rand(0, 61);
         if ($randnum < 10) {
            $randstr .= chr($randnum + 48);
         } else if ($randnum < 36) {
            $randstr .= chr($randnum + 55);
         } else {
            $randstr .= chr($randnum + 61);
         }
      }
      return $randstr;
   }
}
;



$session = new Session;


$form = new Form;

?>