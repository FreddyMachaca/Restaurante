<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

// Cancel Order
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $adn = "DELETE FROM rpos_pedidos WHERE pedido_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        $success = "Deleted" && header("refresh:1; url=payments.php");
    } else {
        $err = "Try Again Later";
    }
}

require_once('partials/_head.php');
?>

<body>
<!-- Sidenav -->
<?php
require_once('partials/_sidebar.php');
?>
<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    ?>
    <!-- Header -->
    <div style="background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
        <span class="mask bg-gradient-dark opacity-8"></span>
        <div class="container-fluid">
            <div class="header-body">
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--8">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <a href="orders.php" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> <i class="fas fa-utensils"></i>
                            Hacer un nuevo pedido
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">CÓDIGO</th>
                                <th scope="col">CLIENTE</th>
                                <th scope="col">PRODUCTO</th>
                                <th scope="col">PRECIO TOTAL</th>
                                <th scope="col">FECHA</th>
                                <th scope="col">ACCIÓN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $ret = "SELECT * FROM rpos_pedidos WHERE pedido_status = '' ORDER BY created_at DESC";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            while ($order = $res->fetch_object()) {
                                $total = ($order->prod_precio * $order->prod_cant);
                                ?>
                                <tr>
                                    <th class="text-success" scope="row"><?php echo $order->pedido_code; ?></th>
                                    <td><?php echo $order->cliente_nombre; ?></td>
                                    <td><?php echo $order->prod_nombre; ?></td>
                                    <td><?php echo $total; ?>Bs</td>
                                    <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                    <td>
                                        <a href="pay_order.php?order_code=<?php echo $order->pedido_code; ?>&customer_id=<?php echo $order->cliente_id; ?>&order_status=PAGADO">
                                            <button class="btn btn-sm btn-success">
                                                <i class="fas fa-handshake"></i>
                                                Orden de pago
                                            </button>
                                        </a>

                                        <a href="payments.php?cancel=<?php echo $order->pedido_id; ?>">
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-window-close"></i>
                                                Cancelar orden
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <?php
        require_once('partials/_footer.php');
        ?>
    </div>
</div>
<!-- Argon Scripts -->
<?php
require_once('partials/_scripts.php');
?>
</body>

</html>
