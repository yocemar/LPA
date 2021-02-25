<?PHP
  require('app-lib.php');
  build_header();
?>
  <?PHP build_navBlock(); ?>
  <div id="content">
    <div class="sectionHeader">Cart</div>
    <?php
        if(isset($_GET['action']) && $_GET['action']=="add"){
            $id=intval($_GET['id']);
            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id]['quantity']++;
            }else{
                openDB();
                $sql_s="SELECT * FROM lpa_stock
                    WHERE lpa_stock_ID={$id}";
                $query_s = $db->query($sql_s);
                $row_cnt = $query_s->num_rows;
                if($row_cnt !=0){
                    $row_s= $query_s->fetch_assoc();
                    $_SESSION['cart'][$row_s['lpa_stock_ID']]=array(
                            "quantity" => 1,
                            "price" => $row_s['lpa_stock_price']
                        );
                }else{
                    $message="This product id it's invalid!";
                }
            }
        }

        if(isset($_GET['action']) && $_GET['action']=="clean"){
            $_SESSION['cart'] = NULL;
        }
    ?>
    <?php

    if(isset($_SESSION['cart'])){
        openDB();
        $sql="SELECT * FROM lpa_stock WHERE lpa_stock_ID IN (";

        foreach($_SESSION['cart'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY lpa_stock_name ASC";
        $query = $db->query($sql);

        while($row = $query->fetch_assoc()){

        ?>
            <p><?php echo $row['lpa_stock_name'] ?> x <?php echo $_SESSION['cart'][$row['lpa_stock_ID']]['quantity'] ?></p>
        <?php

        }
    ?>
        <hr />
        <ul>
            <li><a href="products.php">Go to Products</a></li>
            <li><a href="cart.php?action=clean">Clean Cart</a></li>
            <li><a href="checkout.php">Checkout</a></li>
        </ul>


    <?php

    }else{

        echo "<p>Your Cart is empty. Please add some products.</p>";
        ?> <br /><a href="products.php">Go to Products</a> <?PHP

    }

?>
  </div>
  <script>
    function loadURL(URL) {
      window.location = URL;
    }
  </script>

<?PHP
  build_footer();
?>
