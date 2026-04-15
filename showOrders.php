<?php
include "connectDatabase.php";
include "utilFunctions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - Show Orders</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            background-color: #f8f4ef;
        }

        .headerBox {
            background-color: #d8c3a5;
            color: #3e2c23;
            padding: 20px;
        }

        .mainTitle {
            font-weight: bold;
            margin: 0;
        }

        .subTitle {
            margin-top: 8px;
        }

        .logoBox img {
            max-height: 100px;
        }

        .sectionBox {
            background-color: white;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #d8c3a5;
        }

        .tableHeader {
            background-color: #8e6e53;
            color: white;
        }

        .orderBlock {
            margin-bottom: 30px;
            border: 1px solid #d8c3a5;
            background-color: #fffaf5;
        }

        .orderInfo {
            background-color: #f3e7da;
            padding: 15px;
            border-bottom: 1px solid #d8c3a5;
        }

        .noOrders {
            background-color: #fffaf5;
            border: 1px solid #d8c3a5;
            padding: 20px;
        }
    </style>
</head>

<body>

<div class="w3-content" style="max-width:1400px;">

    <div class="w3-row headerBox">
        <div class="w3-col l9 m8 s12">
            <h1 class="mainTitle">Main Street Boutique</h1>
            <h3 class="subTitle">Show Orders</h3>
        </div>
        <div class="w3-col l3 m4 s12 w3-right-align logoBox">
            <img src="logo.png" alt="Main Street Boutique Logo">
        </div>
    </div>

    <?php
    if (file_exists("mainMenu.php")) {
        include "mainMenu.php";
    }
    ?>

    <div class="sectionBox">
        <h3>Customer Orders</h3>

        <?php
        $sqlOrders = "
            SELECT 
                o.order_id,
                o.user_id,
                o.order_date,
                o.total_amount,
                o.status,
                u.first_name,
                u.last_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            ORDER BY o.order_id DESC
        ";

        $resultOrders = $conn->query($sqlOrders);

        if ($resultOrders && $resultOrders->num_rows > 0) {
            while ($order = $resultOrders->fetch_assoc()) {
                $order_id = $order['order_id'];
                $customerName = htmlspecialchars($order['first_name'] . " " . $order['last_name']);
                $orderDate = htmlspecialchars($order['order_date']);
                $totalAmount = number_format((float)$order['total_amount'], 2);
                $status = htmlspecialchars($order['status']);

                echo "<div class='orderBlock'>";
                echo "<div class='orderInfo'>";
                echo "<strong>Order ID:</strong> " . $order_id . "<br>";
                echo "<strong>Customer:</strong> " . $customerName . "<br>";
                echo "<strong>Order Date:</strong> " . $orderDate . "<br>";
                echo "<strong>Status:</strong> " . $status . "<br>";
                echo "<strong>Total Amount:</strong> $" . $totalAmount;
                echo "</div>";

                $sqlItems = "
                    SELECT 
                        oi.quantity,
                        oi.price,
                        p.product_name,
                        p.category,
                        p.description
                    FROM order_items oi
                    LEFT JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = $order_id
                ";

                $resultItems = $conn->query($sqlItems);

                echo "<table class='w3-table w3-striped w3-bordered'>";
                echo "<tr class='tableHeader'>";
                echo "<th>Product</th>";
                echo "<th>Category</th>";
                echo "<th>Description</th>";
                echo "<th class='w3-right-align'>Quantity</th>";
                echo "<th class='w3-right-align'>Price</th>";
                echo "<th class='w3-right-align'>Line Total</th>";
                echo "</tr>";

                if ($resultItems && $resultItems->num_rows > 0) {
                    while ($item = $resultItems->fetch_assoc()) {
                        $productName = htmlspecialchars($item['product_name']);
                        $category = htmlspecialchars($item['category']);
                        $description = htmlspecialchars($item['description']);
                        $quantity = (int)$item['quantity'];
                        $price = number_format((float)$item['price'], 2);
                        $lineTotal = number_format($item['quantity'] * $item['price'], 2);

                        echo "<tr>";
                        echo "<td>$productName</td>";
                        echo "<td>$category</td>";
                        echo "<td>$description</td>";
                        echo "<td class='w3-right-align'>$quantity</td>";
                        echo "<td class='w3-right-align'>$$price</td>";
                        echo "<td class='w3-right-align'>$$lineTotal</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No items found for this order.</td></tr>";
                }

                echo "</table>";
                echo "</div>";
            }
        } else {
            echo "<div class='noOrders'><strong>No orders found.</strong></div>";
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
