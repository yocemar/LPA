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

    if($chkLogin == false) {
      $msg = "Login failed! Please try again.";
      lpa_log("User {$uName} failed to log in.");
    }

  }
 build_header();
?>
<div id="contentLogin">
    <form name="frmLogin" id="frmLogin" method="post" action="login.php">
        <div class="titleBar">User Registration</div>
        <div id="loginFrame">
            <div class="msgTitle">Please supply your Registration details:</div>
            <div>First name:</div>
            <input type="text" name="fldUsername" id="fldUsername">
            <div> Last name:</div>
            <input type="text" name="fldUsername" id="fldUsername">
            <div>Address:</div>
            <input type="text" name="fldUsername" id="fldUsername">
            <div>Phone Number:</div>
            <input type="number" name="fldUsername" id="fldUsername">
            <div>Username:</div>
            <input type="text" name="fldUsername" id="fldUsername">

            <div>Password:</div>
            <input type="password" name="fldPassword" id="fldPassword">
            <div>Confirm Password:</div>
            <input type="password" name="fldPassword" id="fldPassword">
            <div class="buttonBar">
                <a href="/login.php"><button>Return</button></a>
                <button type="reset" onclick="do_cancel()">Cancel</button>
                <button type="button" onclick="do_register()">Register</button>
            </div>
        </div>
        <input type="hidden" name="a" value="doLogin">
        <div class="buttonBar">


        </div>
    </form>
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