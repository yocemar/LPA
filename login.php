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
    $action = "insertRec";
    if($chkLogin == false) {
      $msg = "Login failed! Please try again.";
      lpa_log("User {$uName} failed to log in.");
    }
    $userID = gen_ID();
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

  }
 build_header();
?>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet"
  id="bootstrap-css">

<div class="container">
  <div class="row main">
    <div class="main-login main-center">

      <div class="well">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#login" data-toggle="tab">Login</a></li>
          <li><a href="#create" data-toggle="tab">Create Account</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane active in" id="login">
            <form name="frmLogin" id="frmLogin" class="form-horizontal" method="POST" action="login.php">
              <fieldset>
                <div id="legend">
                  <legend class="">Login</legend>
                </div>
                <div class="control-group">
                  <!-- Username -->
                  <label class="control-label" for="username">Username</label>
                  <div class="controls">
                    <input type="text" id="fldUsername" name="fldUsername" placeholder="" class="input-xlarge">
                  </div>
                </div>

                <div class="control-group">
                  <!-- Password-->
                  <label class="control-label" for="password">Password</label>
                  <div class="controls">
                    <input type="password" id="fldPassword" name="fldPassword" placeholder="" class="input-xlarge">
                  </div>
                </div>


                <div class="control-group">
                  <!-- Button -->
                  <div class="controls">
                    <button type="button" onclick=" do_login()" class="btn btn-success">Login</button>
                  </div>
                </div>
              </fieldset>
              <input type="hidden" name="a" value="doLogin">
            </form>
          </div>
          <div class="tab-pane fade" id="create">
            <form name="frmUserRec" id="frmUserRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">

              <label>First Name</label>
              <input type="text" name="userFirstName" id="userFirstName" value="" class="input-xlarge">
              <label>Last Name</label>
              <input type="text" name="userLastName" id="userLastName" value="" class="input-xlarge">
              <label>Phone Number</label>
              <input type="number" name="userPhone" id="userPhone" value="" class="input-xlarge">
              <label>Address</label>
              <input type="text" name="userAddress" id="userAddress" value="" class="input-xlarge">
              <label>Username</label>
              <input type="text" name="username" id="username" value="" class="input-xlarge">
              <label>Password</label>
              <input type="password" name="userPassword" id="userPassword" value="" class="input-xlarge">
              <label>Confirm Password</label>
              <input type="password" value="" class="input-xlarge">

              <div>
                <button type="reset" class="btn btn-danger">Cancel</button>
                <button class="btn btn-primary" type="button" id="btnStockSave">Create Account</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
  var msg = " <?PHP echo $msg; ?>";
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

  $("#btnStockSave").click(function () {
    $("#frmUserRec").submit();
  });
</script>
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<?PHP
build_footer();
?>