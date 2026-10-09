<?php

include("include/classes/session.php");

class Process
{

   function __construct()
   {
      global $session;

      if (isset($_POST['sublogin'])) {
         $this->procLogin();
      } else if (isset($_POST['subjoin'])) {
         $this->procRegister();
      } else if (isset($_POST['changepassword'])) {
         $this->changepassword();
      } else if ($session->logged_in) {
         $this->procLogout();
      } else {
         header("Location: index.php");
      }
   }

   function procLogin()
   {
      global $session, $form;
      $retval = $session->login($_POST['user'], $_POST['pass']);

      if ($retval) {
         header("Location: index.php");
      } else {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: login.php");
      }
   }


   function procLogout()
   {
      global $session;
      $retval = $session->logout();
      header("Location: index.php");
   }


   function procRegister()
   {
      global $session, $form;
      if (ALL_LOWERCASE) {
         $_POST['user'] = strtolower($_POST['user']);
      }
      $retval = $session->register($_POST['user'], $_POST['pass'], $_POST['email']);


      if ($retval == 0) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = true;
         header("Location: index.php?success=0");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: " . $session->referrer);
      } else if ($retval == 2) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = false;
         header("Location: index.php?error=2");
      }
   }



   function proaddclass()
   {
      global $session, $form;
      if (ALL_LOWERCASE) {
         $_POST['name'] = strtolower($_POST['name']);
         $_POST['description'] = strtolower($_POST['description']);
      }
      $retval = $session->addclass($_POST['name'], $_POST['description'], $_POST['slots']);


      if ($retval == 0) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = true;
         header("Location: add-class.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: add-class.php?msg=error");
      } else if ($retval == 2) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = false;
         header("Location: add-class.php?msg=db_error");
      }
   }


   function uploadimage()
   {
      global $session, $form;

      // Check if the file was uploaded
      if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
         header("Location: settings.php?msg=upload_error");
         exit;
      }

      // Get file information
      $image = $_FILES['image']['name'];
      $fileTmpPath = $_FILES['image']['tmp_name'];
      $fileType = mime_content_type($fileTmpPath);
      $fileExt = pathinfo($image, PATHINFO_EXTENSION);

      // Define allowed MIME types and extensions
      $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

      // Validate MIME type and extension
      if (!in_array($fileType, $allowedMimeTypes) || !in_array(strtolower($fileExt), $allowedExtensions)) {
         header("Location: settings.php?msg=invalid_image_type");
         exit;
      }

      // Define the upload path
      $path = "images/" . $image;

      // Move the uploaded file to the destination
      if (!move_uploaded_file($fileTmpPath, $path)) {
         header("Location: settings.php?msg=upload_error");
         exit;
      }

      // Call session function to process further
      $retval = $session->uploadimage($_POST['username'], $image);

      if ($retval == 0) {
         header("Location: settings.php?msg=success");
      } elseif ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: settings.php?msg=error");
      } elseif ($retval == 2) {
         header("Location: settings.php?msg=db_error");
      }
   }


   function prochange_accountdetails()
   {
      global $session, $form;




      $retval = $session->change_accountdetails($_POST['username'], $_POST['displayname'], $_POST['email'], $_POST['phone']);


      if ($retval == 0) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = true;
         header("Location: settings.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: settings.php?msg=error");
      } else if ($retval == 2) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = false;
         header("Location: settings.php?msg=db_error");
      }
   }

   function changepassword()
   {
      global $session, $form;




      $retval = $session->changepassword($_POST['username'], $_POST['curpass'], $_POST['newpass']);


      if ($retval == 0) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = true;
         header("Location: settings.php?msg=success");
      } else if ($retval == 1) {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: settings.php?msg=error");
      } else if ($retval == 2) {
         // $_SESSION['reguname'] = $_POST['user'];
         // $_SESSION['regsuccess'] = false;
         header("Location: settings.php?msg=db_error");
      }
   }




   function procEditAccount()
   {
      global $session, $form;

      $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['email']);


      if ($retval) {
         $_SESSION['useredit'] = true;
         header("Location: " . $session->referrer);
      } else {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: " . $session->referrer);
      }
   }
}
;


$process = new Process;

?>