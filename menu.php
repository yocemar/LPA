<?PHP
function build_navBlock() {
isset($_SESSION["isAdmin"])?
$isAdmin = $_SESSION["isAdmin"] :
$isAdmin = "";
?>
<div id="navBlock">
    <div id="navHeader">MAIN MENU</div>
    <div class="navItem" onclick="navMan('index.php')">HOME</div>
    <div class="navItem" onclick="navMan('stock.php')">STOCK MANAGEMENT</div>
    <div class="navItem" onclick="navMan('sales.php')">SALES MANAGEMENT</div>
    <div class="navItem" onclick="navMan('cart.php')">CART</div>
    <div class="navItem" onclick="navMan('client.php')">CLIENTS MANAGEMENT</div>
    <div class="navItem" onclick="navMan('mashup.php')">MASHUP</div>
    <div class="navItem" onclick="navMan('help.php')">HELP</div>


    <?PHP
              if($isAdmin) {
            ?>

    <div class="navTitle">Administration</div>
    <div class="navItem" onclick="navMan('users.php')">USERS</div>
    <?PHP } ?>
    <div class="menuSep"></div>
    <div class="navItem" onclick="navMan('login.php?killses=true')">Logout</div>
</div>
<?PHP
}