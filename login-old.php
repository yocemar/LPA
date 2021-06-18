<?PHP 
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  $msg = null;
  if($action == "doLogin") {
    $chkLogin = false;
    isset($_POST['fldUsername'])?
      $uName = $_POST['fldUsername'] : $uName = "";
    isset($_POST['fldPassword'])?
      $uPassword = $_POST['fldPassword'] : $uPassword = "";

    openDB();
    $query =
      "
      SELECT
        lpa_user_ID,
        lpa_user_username,
        lpa_user_password,
		lpa_user_group
      FROM
        lpa_users
      WHERE
        lpa_user_username = '$uName'
      AND
        lpa_user_password = '$uPassword'
      LIMIT 1
      ";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    if($row['lpa_user_username'] == $uName) {
      if($row['lpa_user_password'] == $uPassword) {
        $_SESSION['authUser'] = $row['lpa_user_ID'];
		$_SESSION['isAdmin'] = (($row['lpa_user_group']=="administrator")?true:false);

    lpa_log("User {$uName} successfully logged in.");
        header("Location: index.php");
        exit;
      }
    }

    if($action == "insertRec") {
      $userPassword = base64_encode($userPassword);
      $query =
        "INSERT INTO lpa_users (
            lpa_user_ID,
            lpa_user_username,
            lpa_user_password,
            lpa_user_firstname,
            lpa_user_lastname,
            lpa_user_group,
            lpa_user_status
         ) VALUES (
           '$userID',
           '$username',
           '$userPassword',
           '$userFirstName',
           '$userLastName',
           '$userGroup',
           '$userStatus'
         )
        ";
      openDB();
      $result = $db->query($query);
      if($db->error) {
        lpa_log($db->error);
        printf("Errormessage: %s\n", $db->error);
        exit;
      } else {
        printf("User {$uName} created user id: {$userID}.");
        header("Location: users.php?a=recInsert&txtSearch=".$userID);
        exit;
      }
    }

    if($chkLogin == false) {
      $msg = "Login failed! Please try again.";
      lpa_log("User {$uName} failed to log in.");
    }

  }
 build_header();
?>
<div>
  <form name="frmLogin" id="frmLogin" method="post" action="login.php">
    <div class="titleBar">User Login</div>
    <div id="loginFrame">
      <div class="msgTitle">Please supply your login details:</div>
      <div>Username:</div>
      <input type="text" name="fldUsername" id="fldUsername">
      <div>Password:</div>
      <input type="password" name="fldPassword" id="fldPassword">
      <div class="buttonBar">

      </div>

    </div>
    <input type="hidden" name="a" value="doLogin">
  </form>


  <div class="buttonBar">
    <a href="registration.php"><button>Registration</button></a>
  </div>
</div>



<script>
  var msg = "<?PHP echo $msg; ?>";
  if (msg) {
    alert(msg);
  }
  $("#contentLogin").center().cs_draggable({
    handle: ".titleBar",
    containment: "window"
  });

  $("#frmLogin").keypress(function (e) {
    if (e.which == 13) {
      $(this).submit();
    }
  });
</script>
<?PHP
build_footer();
?>