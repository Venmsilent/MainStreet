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
        background-color: #f6f1eb;
        color: #3a2a22;
        font-family: Georgia, "Times New Roman", serif;
        margin: 0;
    }

    .page-shell {
        max-width: 1400px;
        margin: 0 auto;
        background-color: #fcf8f4;
        min-height: 100vh;
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    }

    .msb-nav-wrap {
        background-color: #fdfaf6;
        border-bottom: 1px solid #e7ddd2;
    }

    .msb-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 14px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .msb-logo img {
        max-height: 72px;
        width: auto;
        display: block;
    }

    .msb-nav-links {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .msb-nav-btn {
        background-color: transparent !important;
        color: #3b2b23 !important;
        font-size: 16px;
        font-weight: 600;
        padding: 10px 14px !important;
        border-radius: 20px;
    }

    .msb-nav-btn:hover {
        background-color: #f1e7dc !important;
        color: #8a5b3c !important;
    }

    .msb-dropdown-menu {
        background-color: #fffaf5;
        border: 1px solid #e7ddd2;
        border-radius: 10px;
        min-width: 180px;
        overflow: hidden;
        z-index: 1000;
    }

    .msb-dropdown-menu a {
        color: #3b2b23 !important;
        padding: 12px 16px !important;
    }

    .msb-dropdown-menu a:hover {
        background-color: #f3e9df !important;
        color: #8a5b3c !important;
    }

    .pageIntro {
        text-align: center;
        padding: 30px 24px 10px 24px;
    }

    .pageTitle {
        font-size: 42px;
        margin: 0;
        color: #2f2018;
    }

    .pageSubtitle {
        font-size: 18px;
        margin-top: 8px;
        color: #6a5142;
    }

    .sectionBox {
        background-color: #e7ddd2;
        margin: 28px;
        padding: 28px;
        border: 1px solid #fffdfb;
        border-radius: 12px;
    }

    .sectionHeading {
        font-size: 30px;
        margin-top: 0;
        margin-bottom: 8px;
        color: #2f2018;
    }

    .sectionText {
        font-size: 16px;
        color: #6a5142;
        margin-bottom: 24px;
    }

    .subSectionTitle {
        font-size: 26px;
        margin-top: 0;
        margin-bottom: 8px;
        color: #2f2018;
    }

    .productCard {
        border: 1px solid #e7ddd2;
        background-color: #fffaf5;
        padding: 15px;
        margin-bottom: 20px;
        min-height: 420px;
        border-radius: 12px;
    }

    .productCard img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border: 1px solid #ddd;
        background-color: #f2f2f2;
        border-radius: 8px;
    }

    .btnMain {
        background-color: #9b6845;
        color: white;
        border-radius: 24px;
    }

    .btnMain:hover {
        background-color: #7f5235;
        color: white;
    }

    .orderRow {
        border-bottom: 1px solid #e5d8ca;
        padding: 10px 0;
    }

    .w3-select,
    .w3-input {
        border-radius: 8px;
    }

    .w3-dropdown-hover:hover,
    .w3-dropdown-hover:first-child,
    .w3-dropdown-click:hover {
    background-color: transparent !important;
    }

    .messageBox {
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .orderBlock {
    background-color: #fffaf5;
    border: 1px solid #d9ccbe;
    border-radius: 10px;
    padding: 18px;
    margin-bottom: 24px;
}

.orderInfo {
    margin-bottom: 14px;
    line-height: 1.8;
}

.tableHeader {
    background-color: #d8c4b2;
    color: #2f2018;
}

.w3-table {
    background-color: #fffdfb;
}

    @media (max-width: 768px) {
        .msb-nav-inner {
            flex-direction: column;
            align-items: center;
        }

        .msb-nav-links {
            justify-content: center;
        }

        .pageTitle {
            font-size: 32px;
        }

        .sectionBox {
            margin: 18px;
            padding: 18px;
        }

        .msb-logo img {
            max-height: 60px;
        }
    }
</style>
</head>
<body>
    <div class="page-shell">
        <?php include 'mainMenu.php'; ?>
    
        <div class="pageIntro">
        <h1 class="pageTitle">View Orders</h1>
        </div>

    <div class="sectionBox">
        <h2 class="sectionHeading">Customer Orders</h2>
        <?php
            $sqlOrders = "
                SELECT 
                    o.order_id,
                    o.customer_id,
                    o.order_date,
                    o.total_amount,
                    o.status,
                    c.firstName,
                    c.lastName
                FROM orders o
                LEFT JOIN customer c ON o.customer_id = c.customer_id
                ORDER BY o.order_id DESC
            ";

        $resultOrders = $conn->query($sqlOrders);

        if ($resultOrders && $resultOrders->num_rows > 0) {
            while ($order = $resultOrders->fetch_assoc()) {
                $order_id = $order['order_id'];
                $customerName = htmlspecialchars($order['firstName'] . " " . $order['lastName']);
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
            echo "<div class='sectionText'><strong>No orders found.</strong></div>";
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
