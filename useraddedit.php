<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_REQUEST['sid'])? $sid = $_REQUEST['sid'] : $sid = "";
  if(!$sid) {
    isset($_POST['sid'])? $sid = $_POST['sid'] : $sid = "";
  }
  isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  if($action == "delRec") {
    $query =
      "UPDATE lpa_users SET
         lpa_user_status = 'D'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      lpa_log($db->error);
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      printf("User {$uName} deleted user id: {$sid}.");
      header("Location: users.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }

  isset($_POST['txtUserID'])? $userID = $_POST['txtUserID'] : $userID = gen_ID();
  isset($_POST['txtUserFirstName'])? $userFirstName = $_POST['txtUserFirstName'] : $userFirstName = "";
  isset($_POST['txtUserLastName'])? $userLastName = $_POST['txtUserLastName'] : $userLastName = "";
  isset($_POST['txtUsername'])? $username = $_POST['txtUsername'] : $username = "";
  isset($_POST['txtUserGroup'])? $userGroup = $_POST['txtUserGroup'] : $userGroup = "user";
  // isset($_POST['txtUserImage'])? $stockImage = $_POST['txtUserImage'] : $stockImage = "";
  isset($_POST['txtUserPassword'])? $userPassword = $_POST['txtUserPassword'] : $userPassword = "";
  isset($_POST['txtStatus'])? $userStatus = $_POST['txtStatus'] : $userStatus = "a";
  $mode = "insertRec";
  if($action == "updateRec") {
    $userPassword = base64_encode($userPassword);
    $query =
      "UPDATE lpa_users SET
         lpa_user_ID = '$userID',
         lpa_user_username = '$username',
         lpa_user_password = '$userPassword',
         lpa_user_firstname = '$userFirstName',
         lpa_user_lastname = '$userLastName',
         lpa_user_group = '$userGroup',
         lpa_user_status = '$userStatus'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
      lpa_log($db->error);
       printf("Errormessage: %s\n", $db->error);
       exit;
     } else {
      printf("User {$username} updated user id: {$userid}.");
         header("Location: users.php?a=recUpdate&txtSearch=$txtSearch");
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

  if($action == "Edit") {
    $query = "SELECT * FROM lpa_users WHERE lpa_user_ID = '$sid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $userID = $row['lpa_user_ID'];
    $username = $row['lpa_user_username'];
    $userFirstName = $row['lpa_user_firstname'];
    $userLastName = $row['lpa_user_lastname'];
    $userPassword = base64_decode($row['lpa_user_password']);
    $userGroup = $row['lpa_user_group'];
    $userStatus = $row['lpa_user_status'];
    $mode = "updateRec";
  }
  build_header($displayName);
  build_navBlock();
  $fieldSpacer = "5px";
?>

  <div id="content">
    <div class="PageTitle">User Management (<?PHP echo $action; ?>)</div>
    <form name="frmUserRec" id="frmUserRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <input name="txtUserID" id="txtUserID" placeholder="User ID" value="<?PHP echo $userID; ?>" style="width: 100px;" title="Stock ID">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUsername" id="txtUsername" placeholder="User Name" value="<?PHP echo $username; ?>" style="width: 400px;"  title="User Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserFirstName" id="txtUserFirstName" placeholder="User First Name" value="<?PHP echo $userFirstName; ?>" style="width: 400px;"  title="User First Namee">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserLastName" id="txtUserLastName" placeholder="User Last Name" value="<?PHP echo $userLastName; ?>" style="width: 400px;"  title="User Last Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserPassword" id="txtUserPassword" type="password" placeholder="User Password" value="<?PHP echo $userPassword; ?>" style="width: 400px;"  title="User Password">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <select name="txtUserGroup" id="txtUserGroup" style="width: 400px;"  title="User Group">
          <option value="user">User</option>
          <option value="administrator">Administrator</option>
        </select>
      </div>

      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>User Status:</div>
        <input name="txtStatus" id="txtUserStatusActive" type="radio" value="a">
          <label for="txtUserStatusActive">Active</label>
        <input name="txtStatus" id="txtUserStatusInactive" type="radio" value="i">
          <label for="txtUserStatusInactive">Inactive</label>
      </div>
      <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
      <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
      <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
    </form>
    <div class="optBar">
      <button type="button" id="btnStockSave">Save</button>
      <button type="button" onclick="navMan('users.php')">Close</button>
      <?PHP if($action == "Edit") { ?>
      <button type="button" onclick="delRec('<?PHP echo $sid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
      <?PHP } ?>
    </div>
  </div>
  <script>
    var userRecStatus = "<?PHP echo $userStatus; ?>";
    if(userRecStatus == "a") {
      $('#txtUserStatusActive').prop('checked', true);
    } else {
      $('#txtUserStatusInactive').prop('checked', true);
    }

    if ("<?PHP echo $userGroup; ?>" == "user") {
      $("select#txtUserGroup").val("user");
    } else {
      $("select#txtUserGroup").val("administrator");
    }
    $("#btnStockSave").click(function(){
        $("#frmUserRec").submit();
    });
    function delRec(ID) {
      navMan("useraddedit.php?sid=" + ID + "&a=delRec");
    }
    setTimeout(function(){
      $("#txtUsername").focus();
    },1);
  </script>
<?PHP
build_footer();
?>
