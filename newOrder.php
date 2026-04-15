<?php
include "utilFunctions.php";
include "connectDatabase.php";

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
        <h1 class="pageTitle">Add Order</h1>
    </div>

    <div class="sectionBox">

        <form action="newOrder.php" method="post" id="orderForm">

            <h2 class="sectionHeading">Start a New Order</h2>
            <p class="sectionText">Select a customer, browse products, and add items to the order.</p>

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
