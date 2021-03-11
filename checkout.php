<?PHP
  require('app-lib.php');
  build_header();
?>
<?PHP build_navBlock(); ?>
<div id="content">
  <div class="sectionHeader">Checkout</div>
  <?php

    if(isset($_SESSION['cart'])){
        openDB();
        $sql="SELECT * FROM lpa_stock WHERE lpa_stock_ID IN (";

        foreach($_SESSION['cart'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY lpa_stock_name ASC";
        $query = $db->query($sql);
        ?>

  <div id="container">
    <div class="invoice-top">
      <section id="memo">
        <div class="logo">

        </div>

        <div class="company-info">
          <span class="company-name">LPA ECOMMS</span>

          <span class="spacer"></span>

          <div>Brisbane City QLD 4000</div>
          <div>333 Adelaide St, </div>
          <div>0123 456 789</div>
          <div>lpa_ecomms@email.com</div>
        </div>

      </section>

    </div>

    <div class="invoice-body">
      <section id="items">

        <table class="table table-striped" cellpadding="0" cellspacing="0">
          <thead class="table-dark">
            <tr>
              <th>ID</th> <!-- Dummy cell for the row number and row commands -->
              <th>Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <?php
                  $count = 0;
                  $total = 0;
                ?>
          <?php while($row = $query->fetch_assoc()){ ?>
          <?php
                    $count = $count + 1;
                    $sub_total = $row['lpa_stock_price'] * $_SESSION['cart'][$row['lpa_stock_ID']]['quantity'];
                    $total = $total + $sub_total;
                  ?>
          <tr data-iterate="item">
            <td><?php echo $count; ?></td> <!-- Don't remove this column as it's needed for the row commands -->
            <td><?php echo $row['lpa_stock_name']; ?></td>
            <td><?php echo $_SESSION['cart'][$row['lpa_stock_ID']]['quantity']; ?></td>
            <td><?php echo $row['lpa_stock_price']; ?></td>
            <td><?php echo $sub_total; ?></td>
          </tr>
          <?php } ?>

        </table>

      </section>

      <section id="sums">



        <table cellpadding="0" cellspacing="0">
          <tr class="amount-total">
            <th>
              <p style="font-size:15px;"><b>Total</b></p>
            </th>
            <th style="text-align: right;">
              <p style="font-size:15px;"><b><?php echo $total ?></b></p>
            </th>
          </tr>
        </table>

      </section>

    </div>
  </div>
  <div class="clearfix"></div>
  <hr />

  <div class="btn-group">
    <a href="products.php" class="btn btn-primary">Go to Products</a>
    <a href="cart.php?action=clean" class="btn btn-danger">Clean Cart</a>
    <a href="#" class="btn btn-success">Pay</a>
  </div>

  <?php

    }else{

        echo "<p>Your Cart is empty. Please add some products.</p>";
        ?> <br /><a href="products.php">Go to Products</a>
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