<?php

include("constants.php");

class MySQLDB
{
   var $connection;
   var $num_active_users;
   var $num_active_guests;
   var $num_members;

   function __construct()
   {

      $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());


      $this->num_members = -1;

      if (TRACK_VISITORS) {

         $this->calcNumActiveUsers();


         $this->calcNumActiveGuests();
      }
   }

   function confirmUserPass($username, $password)
   {
      // Fetch the hashed password from the database
      $q = "SELECT password FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || mysqli_num_rows($result) < 1) {
         return 1; // User not found
      }

      $dbarray = mysqli_fetch_assoc($result);
      $hashedPassword = $dbarray['password'];

      // Verify the plain password against the hash
      if (password_verify($password, $hashedPassword)) {
         return 0; // Password is correct
      } else {
         return 2; // Password incorrect
      }
   }




   function loginsession($subuser)
   {
      // $username = str_replace('&lt;',"~",str_replace('<',"~&gt;",strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $q = "SELECT * FROM users where username ='$subuser'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || (mysqli_num_rows($result) < 1)) {
         return NULL;
      }

      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
   }

   function confirmUserID($username, $userid)
   {

      $q = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      if (!$result || (mysqli_num_rows($result) < 1)) {
         return 1;
      }


      $dbarray = mysqli_fetch_array($result);
      $dbarray['userid'] = stripslashes($dbarray['userid']);
      $userid = stripslashes($userid);


      if ($userid == $dbarray['userid']) {
         return 0;
      } else {
         return 2;
      }
   }


   function usernameTaken($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   function classnameTaken($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT name FROM classes WHERE name = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   function titlenameTaken($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT title FROM tasks WHERE title = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   function emailTaken($email)
   {

      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT email FROM admissions WHERE email = '$email'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   function sessiontitleTaken($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT title FROM sessions WHERE title = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   // Check if start_time is already used for this course
   function sessionstarttimeTaken($start_time)
   {
      $start_time = mysqli_real_escape_string($this->connection, $start_time);
      // $course_id = (int)$course_id;

      $q = "SELECT * FROM sessions WHERE start_time = '$start_time'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   // Check if end_time is already used for this course
   function sessionendtimeTaken($end_time)
   {
      $end_time = mysqli_real_escape_string($this->connection, $end_time);
      // $course_id = (int)$course_id;

      $q = "SELECT * FROM sessions WHERE end_time = '$end_time'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }

   function slotsfull($session_id)
   {
      $session_id = (int) $session_id; // ensure it is integer

      $q = "SELECT slots, booked_slots FROM sessions WHERE id = '$session_id' LIMIT 1";
      $result = mysqli_query($this->connection, $q);



      $row = mysqli_fetch_assoc($result);
      $slots = (int) $row['slots'];
      $booked = (int) $row['booked_slots'];

      // Return true if session is full
      return ($booked >= $slots);
   }


   function coursenameTaken($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      // if(!get_magic_quotes_gpc()){
      //    $username = addslashes($username);
      // }
      $q = "SELECT course_id FROM fees WHERE course_id = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }


   function coursetypefeeset($type, $course_id)
   {
      $q = "SELECT type,course_id FROM fees WHERE type = '$type' AND course_id = '$course_id'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }


   function usernameBanned($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      if (!get_magic_quotes_gpc()) {
         $username = addslashes($username);
      }
      $q = "SELECT username FROM " . TBL_BANNED_USERS . " WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);
      return (mysqli_num_rows($result) > 0);
   }


   // function getinactiveusers($username)
   // {
   //    // Escape the username to prevent SQL injection
   //    $username = mysqli_real_escape_string($this->connection, $username);

   //    // Check if the user exists in the inactive_users table
   //    $q = "SELECT username FROM inactive_users WHERE username = '$username' LIMIT 1";
   //    $result = mysqli_query($this->connection, $q);



   //    if (mysqli_num_rows($result) > 0) {
   //       // User found in inactive_users
   //       return 2; // Account inactive
   //    }

   //    // User not in inactive_users
   //    return 0; // Active user
   // }



   ////// START Custom Functions


   function clientdata($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $q = "SELECT * FROM users where username='$username'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || (mysqli_num_rows($result) < 1)) {
         return NULL;
      }

      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
   }


   function addNewUser($username, $password, $email)
   {
      $time = time();

      // Hash the password securely
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Escape values to reduce SQL injection risk
      $username = mysqli_real_escape_string($this->connection, $username);
      $email = mysqli_real_escape_string($this->connection, $email);
      $hashedPassword = mysqli_real_escape_string($this->connection, $hashedPassword);

      $q = "INSERT INTO " . TBL_USERS . " 
          VALUES ('$username', '$hashedPassword', '0', '0', '$email', '$time', '')";

      return mysqli_query($this->connection, $q);
   }


   function addclass($name, $description, $slots)
   {

      $time = time();

      $q = "INSERT INTO `classes`(`id`, `name`, `description`, `slots`, `created_at`) VALUES ('','$name','$description','$slots',NOW())";
      return mysqli_query($this->connection, $q);
   }


   function uploadimage($username, $image)
   {

      $currentTime = date('Y-m-d H:i:s');

      $q = "UPDATE `users` SET `parent_directory`='$image' WHERE username = '$username'";
      return mysqli_query($this->connection, $q);
   }


   function change_accountdetails($username, $displayname, $email, $phone)
   {

      $currentTime = date('Y-m-d H:i:s');

      $q = "UPDATE `users` SET `email`='$email',`display_name`='$displayname',`phone` = '$phone' WHERE username = '$username'";
      return mysqli_query($this->connection, $q);
   }


   function changepassword($username, $curpass, $newpass)
   {

      $currentTime = date('Y-m-d H:i:s');
      $hashed_pass = password_hash($newpass, PASSWORD_DEFAULT);
      $q = "UPDATE `users` SET `password` = '$hashed_pass' WHERE username = '$username'";
      return mysqli_query($this->connection, $q);
   }



   function updateUserField($username, $field, $value)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $field = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $field))));
      $value = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $value))));
      $q = "UPDATE " . TBL_USERS . " SET " . $field . " = '$value' WHERE username = '$username'";
      return mysqli_query($this->connection, $q);
   }








   function groupdata($type, $CSRF_Code)
   {
      $type = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $type))));
      $CSRF_Code = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $CSRF_Code))));

      // $jumo2 = md5($_SESSION['CSRF_Code'].'8j5j&*&K5jrffgF9wAJDIH'.'JKHds998954(*)(*dfkjll');

      // if($CSRF_Code == $jumo2){
// in CKRF

      if ($type == "stp_fieldset") {
         $q = "SELECT username FROM users order by username ASC";
         // }


         // End in CKRF
      }



      // Out of CKRF
      if ($type == "stp_fieldset_more") {
         $q = "SELECT username FROM users order by username ASC";
      }

      //custom 
      if ($type == "courses") {
         $q = "SELECT * FROM courses";
      }
      if ($type == "categories_dropdown") {
         $q = "SELECT * FROM categories";
      }
      if ($type == "courses_dropdown") {
         $q = "SELECT * FROM courses";
      }



      //start query process



      $result = mysqli_query($this->connection, $q);
      $num_rows = mysqli_num_rows($result);
      if (!$result || ($num_rows < 0)) {
         echo "";
         return;
      }
      if ($num_rows == 0) {
         echo "";
         return;
      }


      for ($i = 0; $i < $num_rows; $i++) {

         mysqli_data_seek($result, $i);
         $row = mysqli_fetch_assoc($result);

         //END query process


         // if($CSRF_Code == $jumo2){
// //In CKRF
// if($type == "stp_fieldset"){
// echo $row[0];
// }

         // //END In CKRF
// }




         //Out of CKRF

         if ($type == "stp_fieldset_getnamereg") {
            echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
         }

         //Custom

         if ($type == "stp_fieldset_more") {
            echo '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td></tr>';
         }

         if ($type == "courses") {
            echo '

         <div class="bg-white rounded-2xl overflow-hidden card-shadow hover-scale transition-all duration-300 hover:shadow-2xl">
    <!-- Course Image/Icon -->
    <div class="relative h-48 gradient-bg flex items-center justify-center overflow-hidden">
    <img src="courses/' . $row['image'] . '" 
         class="h-24 w-24 rounded-full transition-transform duration-300 hover:scale-110"
         alt="' . htmlspecialchars($row['course_title']) . '">
</div>

    
    <!-- Course Content -->
    <div class="p-6">
        <!-- Duration & Category Tags -->
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <span class="bg-purple-100 text-purple-600 px-3 py-1.5 rounded-full text-sm font-semibold inline-flex items-center">
                <i class="far fa-clock mr-1.5"></i>
                Minimum ' . htmlspecialchars($row['duration']) . ' Months
            </span>
            
            
        </div>
        
        <!-- Course Title -->
        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-purple-600 transition-colors">
            <a href="course-details.php?id=' . $row['id'] . '" class="block">
                ' . htmlspecialchars($row['course_title']) . '
            </a>
        </h3>
        
        <!-- Course Description -->
        <p class="text-gray-600 mb-6 line-clamp-3 text-sm leading-relaxed">
            ' . htmlspecialchars($row['description']) . '
        </p>
        
       
        
        <!-- Action Buttons -->
        <div class="flex gap-2 sm:gap-3">
    <a href="course-detail.php?id=' . $row['id'] . '" 
       class="flex-1 sm:flex-1 text-center 
              bg-gradient-to-r from-purple-600 to-indigo-600 text-white 
              px-2 py-1.5 sm:px-4 sm:py-3 
              rounded-lg sm:rounded-xl 
              text-xs sm:text-base font-semibold 
              hover:from-purple-700 hover:to-indigo-700 
              transition-all duration-300 shadow-md hover:shadow-lg">
        <i class="fas fa-info-circle mr-1 sm:mr-2"></i>
        View
    </a>

    <a href="admission.php?course_id=' . $row['id'] . '" 
       class="flex-1 sm:flex-1 text-center 
              bg-white border-2 border-purple-600 text-purple-600 
              px-2 py-1.5 sm:px-4 sm:py-3 
              rounded-lg sm:rounded-xl 
              text-xs sm:text-base font-semibold 
              hover:bg-purple-50 transition-all duration-300">
        <i class="fas fa-arrow-right mr-1 sm:mr-2"></i>
        Enroll
    </a>
</div>

    </div>
</div>
       ';
         }

         if ($type == "categories_dropdown") {
            echo '

          <option value=' . $row['id'] . '>' . $row['category'] . '</option>
       ';
         }
         if ($type == "courses_dropdown") {
            echo '

          <option value=' . $row['id'] . '>' . $row['course_title'] . '</option>
       ';
         }


         // END Out of CKRF
      }
   }





   ///////END Custom Functions



   //project
   function getslots($class_id)
   {
      $class_id = mysqli_real_escape_string($this->connection, $class_id);

      $q = "SELECT slots FROM classes WHERE id = '$class_id' LIMIT 1";
      $result = mysqli_query($this->connection, $q);

      if (!$result || mysqli_num_rows($result) == 0) {
         return null;
      }

      $row = mysqli_fetch_assoc($result);
      return $row['slots'];  // return number like 13
   }




   function numberofclasses()
   {

      $q = "SELECT * FROM classes";
      $result = mysqli_query($this->connection, $q);
      return mysqli_num_rows($result);

   }


   function numberOfCoursesByStudent($registration_no)
   {
      // Escape string input
      $registration_no = mysqli_real_escape_string($this->connection, $registration_no);

      // Count courses associated with this student
      $q = "SELECT COUNT(*) AS total_courses 
          FROM students 
          WHERE registration_no = '$registration_no'"; // adjust column name if needed

      $result = mysqli_query($this->connection, $q);
      $row = mysqli_fetch_assoc($result);

      return (int) $row['total_courses'];
   }

   function getUserInfo($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $q = "SELECT * FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || (mysqli_num_rows($result) < 1)) {
         return NULL;
      }

      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
   }


   function getUserOnly($username)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || (mysqli_num_rows($result) < 1)) {
         return NULL;
      }

      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
   }

   function getclassdetails($id)
   {
      $id = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $id))));
      $q = "SELECT * FROM classes WHERE id = '$id'";
      $result = mysqli_query($this->connection, $q);

      if (!$result || (mysqli_num_rows($result) < 1)) {
         return NULL;
      }

      $dbarray = mysqli_fetch_array($result);
      return $dbarray;
   }


   function getNumMembers()
   {
      if ($this->num_members < 0) {
         $q = "SELECT * FROM " . TBL_USERS;
         $result = mysqli_query($this->connection, $q);
         $this->num_members = mysqli_num_rows($result);
      }
      return $this->num_members;
   }


   function calcNumActiveUsers()
   {

      $q = "SELECT * FROM " . TBL_ACTIVE_USERS;
      $result = mysqli_query($this->connection, $q);
      $this->num_active_users = mysqli_num_rows($result);
   }

   function calcNumActiveGuests()
   {

      $q = "SELECT * FROM " . TBL_ACTIVE_GUESTS;
      $result = mysqli_query($this->connection, $q);
      $this->num_active_guests = mysqli_num_rows($result);
   }

   function addActiveUser($username, $time)
   {
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      $time = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $time))));
      $q = "UPDATE " . TBL_USERS . " SET timestamp = '$time' WHERE username = '$username'";
      mysqli_query($this->connection, $q);

      if (!TRACK_VISITORS)
         return;
      $q = "REPLACE INTO " . TBL_ACTIVE_USERS . " VALUES ('$username', '$time')";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveUsers();
   }


   function addActiveGuest($ip, $time)
   {
      $ip = (string)($ip ?? '127.0.0.1');
      $time = (string)($time ?? time());
      $ip = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $ip))));
      $time = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $time))));
      if (!TRACK_VISITORS)
         return;
      $q = "REPLACE INTO " . TBL_ACTIVE_GUESTS . " VALUES ('$ip', '$time')";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveGuests();
   }


   function removeActiveUser($username)
   {
      $username = (string)($username ?? '');
      $username = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $username))));
      if (!TRACK_VISITORS)
         return;
      $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE username = '$username'";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveUsers();
   }


   function removeActiveGuest($ip)
   {
      $ip = str_replace('&lt;', "~", str_replace('<', "~&gt;", strip_tags(mysqli_real_escape_string($this->connection, $ip))));
      if (!TRACK_VISITORS)
         return;
      $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE ip = '$ip'";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveGuests();
   }


   function removeInactiveUsers()
   {
      if (!TRACK_VISITORS)
         return;
      $timeout = time() - USER_TIMEOUT * 60;
      $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE timestamp < $timeout";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveUsers();
   }


   function removeInactiveGuests()
   {
      if (!TRACK_VISITORS)
         return;
      $timeout = time() - GUEST_TIMEOUT * 60;
      $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE timestamp < $timeout";
      mysqli_query($this->connection, $q);
      $this->calcNumActiveGuests();
   }


   function query($query)
   {
      return mysqli_query($this->connection, $query);
   }

   function numOfTeamMembers()
   {
      $q = "SELECT * FROM team";
      return mysqli_num_rows(mysqli_query($this->connection, $q));
   }
   function getEnrolledCourses($courseID)
   {
      $q = "SELECT course FROM enrolled_course WHERE course_id = '$courseID'";
      $array = mysqli_fetch_all(mysqli_query($this->connection, $q));
      $count = count($array);
      $courses = "";
      for ($i = 0; $i < $count; $i++) {
         if ($i == 0) {
            $courses = $array[$i][0];
         } else {
            $courses = $array[$i][0] . ", " . $courses;
         }
      }
      return $courses;
   }

   function declinedproject($id)
   {
      $q = "UPDATE `project` SET `status`='declined' WHERE id = '$id'";
      return mysqli_query($this->connection, $q);
   }
   // function updateproject($clientname){
   //    $q = "UPDATE `project` SET `status`='declined' WHERE client_namr = '$clientname'";
   //    return mysqli_query($this->connection, $q);
   // }

   function deleteTeamMember($id)
   {
      $q = "DELETE FROM team WHERE id = '$id'";
      return mysqli_query($this->connection, $q);
   }

}
;


$database = new MySQLDB;

?>