<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  if(!$action) {
    isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  build_header($displayName);
?>
  <?PHP build_navBlock(); ?>
  <div id="content">
    <div class="PageTitle">Client Search</div>

  <!-- Search Section Start -->
    <form name="frmSearchClient" method="post"
          id="frmSearchClient"
          action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div class="displayPane">
        <div class="displayPaneCaption">Search:</div>
        <div>
          <input name="txtSearch" id="txtSearch" placeholder="Search Client"
          style="width: calc(100% - 115px)" value="<?PHP echo $txtSearch; ?>">
          <button type="button" id="btnSearch">Search</button>
          <button type="button" id="btnAddRec">Add</button>
        </div>
      </div>
      <input type="hidden" name="a" value="listClient">
    </form>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listClient") {
    ?>
    <div>
      <table style="width: calc(100% - 15px);border: #cccccc solid 1px">
        <tr style="background: #eeeeee">
          <td style="width: 80px;border-left: #cccccc solid 1px"><b>Client ID</b></td>
          <td style="width: 80px;border-left: #cccccc;"><b>Clientname</b></td>          
          <td style="border-left: #cccccc solid 1px"><b>Client Full Name</b></td>
        </tr>
    <?PHP
      openDB();
      $query =
        "SELECT
            *
         FROM
            lpa_clients
         WHERE
            lpa_client_ID LIKE '%$txtSearch%' AND lpa_client_status <> 'D'
         OR
            lpa_client_phone LIKE '%$txtSearch%' AND lpa_client_status <> 'D'
         ";
      $result = $db->query($query);
     
      $row_cnt = $result->num_rows;

      if($row_cnt >= 1) {
        while ($row = $result->fetch_assoc()) {
          $sid = $row['lpa_client_ID'];
          ?>
          <tr class="hl">
            <td onclick="loadClient(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
              <?PHP echo $sid; ?>
            </td>
            <td onclick="loadClient(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_client_phone']; ?>
            </td>
            <td style="">
              <?PHP echo $row['lpa_client_firstname'] . " " . $row['lpa_client_lastname']; ?>
            </td>
          </tr>
        <?PHP }
      } else { ?>
        <tr>
          <td colspan="4" style="text-align: center">
            No Records Found for: <b><?PHP echo $txtSearch; ?></b>
          </td>
        </tr>
      <?PHP } ?>
      </table>
    </div>
    <?PHP } ?>
    <!-- Search Section List End -->
  </div>
  <script>
    var action = "<?PHP echo $action; ?>";
    var search = "<?PHP echo $txtSearch; ?>";
    if(action == "recUpdate") {
      alert("Record Updated!");
      navMan("clients.php?a=listClient&txtSearch=" + search);
    }
    if(action == "recInsert") {
      alert("Record Added!");
      navMan("clients.php?a=listClient&txtSearch=" + search);
    }
    if(action == "recDel") {
      alert("Record Deleted!");
      navMan("clients.php?a=listClient&txtSearch=" + search);
    }
    function loadClient(ID,MODE) {
      window.location = "Clientaddedit.php?sid=" +
      ID + "&a=" + MODE + "&txtSearch=" + search;
    }
    $("#btnSearch").click(function() {
      $("#frmSearchClient").submit();
    });
    $("#btnAddRec").click(function() {
      loadClient("","Add");
    });
    setTimeout(function(){
      $("#txtSearch").select().focus();
    },1);
  </script>
<?PHP
build_footer();
?>