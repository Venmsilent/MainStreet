<?php
include "utilFunctions.php";
include "connectDatabase.php";

$message = "";

if (isset($_POST['finishOrder'])) {
    $user_id = isset($_POST['customer']) ? (int)$_POST['customer'] : 0;
    $product_ids = isset($_POST['product_id']) ? $_POST['product_id'] : array();
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : array();

    if ($user_id <= 0) {
        $message = "Please select a customer.";
    } elseif (count($product_ids) == 0) {
        $message = "Please add at least one product to the order.";
    } else {
        $total_amount = 0.00;
        $validItems = array();

        for ($i = 0; $i < count($product_ids); $i++) {
            $pid = (int)$product_ids[$i];
            $qty = (int)$quantities[$i];

            if ($pid > 0 && $qty > 0) {
                $sql = "SELECT product_id, product_name, price FROM products WHERE product_id = $pid";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $lineTotal = $row['price'] * $qty;
                    $total_amount += $lineTotal;

                    $validItems[] = array(
                        "product_id" => $row['product_id'],
                        "product_name" => $row['product_name'],
                        "quantity" => $qty,
                        "price" => $row['price']
                    );
                }
            }
        }

        if (count($validItems) == 0) {
            $message = "No valid products were added.";
        } else {
            $status = "pending";

            $sql = "INSERT INTO orders (user_id, order_date, total_amount, status)
                    VALUES ($user_id, NOW(), $total_amount, '$status')";

            if ($conn->query($sql) === TRUE) {
                $order_id = $conn->insert_id;

                foreach ($validItems as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];

                    $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price)
                                VALUES ($order_id, $product_id, $quantity, $price)";
                    $conn->query($sqlItem);
                }

                $message = "Order successfully created. Order ID: " . $order_id;
            } else {
                $message = "Error creating order: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - New Order</title>
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

        .productCard {
            border: 1px solid #d8c3a5;
            background-color: #fffaf5;
            padding: 15px;
            margin-bottom: 20px;
            min-height: 420px;
        }

        .productCard img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border: 1px solid #ddd;
            background-color: #f2f2f2;
        }

        .btnMain {
            background-color: #8e6e53;
            color: white;
        }

        .btnMain:hover {
            background-color: #6f5440;
            color: white;
        }

        .orderRow {
            border-bottom: 1px solid #ddd;
            padding: 8px 0;
        }
    </style>
</head>
<body>

<div class="w3-content" style="max-width:1400px;">

    <div class="w3-row headerBox">
        <div class="w3-col l9 m8 s12">
            <h1 class="mainTitle">Main Street Boutique</h1>
            <h3 class="subTitle">New Order / Shopping Page</h3>
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
        <?php
        if ($message != "") {
            echo "<div class='w3-panel w3-pale-yellow w3-border'>" . $message . "</div>";
        }
        ?>

        <form action="newOrder.php" method="post" id="orderForm">

            <label><strong>Customer</strong></label>
            <select class="w3-select w3-border" name="customer" id="customer" required>
                <option value="" disabled selected>Choose Customer</option>
                <?php
                $sqlUsers = "SELECT user_id, first_name, last_name FROM users ORDER BY last_name, first_name";
                $resultUsers = $conn->query($sqlUsers);

                if ($resultUsers && $resultUsers->num_rows > 0) {
                    while ($rowUser = $resultUsers->fetch_assoc()) {
                        $uid = $rowUser['user_id'];
                        $fname = $rowUser['first_name'];
                        $lname = $rowUser['last_name'];

                        echo "<option value='$uid'>$uid - $lname, $fname</option>";
                    }
                }
                ?>
            </select>

            <div class="sectionBox">
                <h3>Available Products</h3>
                <div class="w3-row-padding">
                    <?php
                    $sqlProducts = "SELECT product_id, product_name, category, description, price, image_path
                                    FROM products
                                    ORDER BY product_id";
                    $resultProducts = $conn->query($sqlProducts);

                    if ($resultProducts && $resultProducts->num_rows > 0) {
                        while ($row = $resultProducts->fetch_assoc()) {
                            $product_id = $row['product_id'];
                            $product_name = htmlspecialchars($row['product_name']);
                            $category = htmlspecialchars($row['category']);
                            $description = htmlspecialchars($row['description']);
                            $price = formatPrice($row['price']);
                            $image_path = htmlspecialchars($row['image_path']);

                            echo "<div class='w3-third'>";
                            echo "<div class='productCard'>";
                            echo "<img src='$image_path' alt='$product_name'>";
                            echo "<h4>$product_name</h4>";
                            echo "<p><strong>Category:</strong> $category</p>";
                            echo "<p><strong>Price:</strong> $$price</p>";
                            echo "<p>$description</p>";
                            echo "<p><a href='productReviews.php?product_id=$product_id'>View Reviews</a></p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="sectionBox">
                <div class="w3-row-padding">
                    <div class="w3-half">
                        <label><strong>Add Product</strong></label>
                        <select class="w3-select w3-border" id="productSelect">
                            <option value="" disabled selected>Choose Product</option>
                            <?php
                            $sqlProductsDropdown = "SELECT product_id, product_name, price FROM products ORDER BY product_id";
                            $resultProductsDropdown = $conn->query($sqlProductsDropdown);

                            if ($resultProductsDropdown && $resultProductsDropdown->num_rows > 0) {
                                while ($rowProduct = $resultProductsDropdown->fetch_assoc()) {
                                    $productId = $rowProduct['product_id'];
                                    $productName = $rowProduct['product_name'];
                                    $productPrice = formatPrice($rowProduct['price']);

                                    echo "<option value='$productId' data-name=\"" . htmlspecialchars($productName) . "\" data-price='$productPrice'>";
                                    echo $productId . " - " . htmlspecialchars($productName) . " - $" . $productPrice;
                                    echo "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="w3-quarter">
                        <label><strong>Quantity</strong></label>
                        <input class="w3-input w3-border" type="number" id="quantityInput" min="1" value="1">
                    </div>

                    <div class="w3-quarter" style="padding-top:24px;">
                        <button class="w3-button btnMain w3-block" type="button" onclick="addProductToOrder()">Add To Order</button>
                    </div>
                </div>

                <br>

                <h3>Current Order</h3>
                <div id="orderItemsBox">
                    <p id="emptyOrderText">No products added yet.</p>
                </div>

                <br>
                <label><strong>Total: $</strong></label>
                <input class="w3-input w3-border" type="text" id="totalDisplay" value="0.00" readonly>

                <br>
                <button class="w3-button btnMain" type="submit" name="finishOrder">Finish Order</button>
            </div>

        </form>
    </div>
</div>

<script>
let runningTotal = 0;

function addProductToOrder() {
    const productSelect = document.getElementById("productSelect");
    const quantityInput = document.getElementById("quantityInput");
    const orderItemsBox = document.getElementById("orderItemsBox");
    const totalDisplay = document.getElementById("totalDisplay");
    const emptyOrderText = document.getElementById("emptyOrderText");

    if (productSelect.selectedIndex <= 0) {
        alert("Please choose a product.");
        return;
    }

    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const productId = selectedOption.value;
    const productName = selectedOption.getAttribute("data-name");
    const productPrice = parseFloat(selectedOption.getAttribute("data-price"));
    const quantity = parseInt(quantityInput.value);

    if (quantity <= 0 || isNaN(quantity)) {
        alert("Please enter a valid quantity.");
        return;
    }

    if (emptyOrderText) {
        emptyOrderText.remove();
    }

    const lineTotal = productPrice * quantity;
    runningTotal += lineTotal;
    totalDisplay.value = runningTotal.toFixed(2);

    const row = document.createElement("div");
    row.className = "orderRow";

    row.innerHTML =
        "<strong>" + productName + "</strong> " +
        "(ID: " + productId + ") - $" + productPrice.toFixed(2) +
        " x " + quantity +
        " = $" + lineTotal.toFixed(2) +
        "<input type='hidden' name='product_id[]' value='" + productId + "'>" +
        "<input type='hidden' name='quantity[]' value='" + quantity + "'>";

    orderItemsBox.appendChild(row);

    quantityInput.value = 1;
    productSelect.selectedIndex = 0;
}
</script>

</body>
</html>
<?php
$conn->close();
?>
