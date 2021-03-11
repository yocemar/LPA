<?PHP
  require('app-lib.php');
  build_header();
?>
<?PHP build_navBlock(); ?>
<div id="content">
    <div class="sectionHeader">Cart</div>

    <table class="table">
        <thead>
            <tr>

                <th scope="col">Item</th>
                <th scope="col">Quantity</th>

            </tr>
        </thead>
        <tbody>

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

            <tr>

                <td> <?php echo $row['lpa_stock_name'] ?> </td>
                <td> <?php echo $_SESSION['cart'][$row['lpa_stock_ID']]['quantity'] ?></td>
            </tr>


            <?php

        } 
    ?>
        <tbody>
    </table>
    <hr />
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
        <a href="products.php" class="btn btn-primary">Go to Products</a>
        <a href="cart.php?action=clean" class="btn btn-danger">Clean Cart</a>
        <a href="checkout.php" class="btn btn-success">Checkout</a>
    </div>


    <?php

    }else{

        
        ?> <br>
    <div class="alert alert-primary" role="alert">
        <b> Your Cart is empty. Please add some products. </b>
    </div>
    <br /><a href=" products.php" class="btn btn-primary">Go to Products</a>
    <?PHP

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