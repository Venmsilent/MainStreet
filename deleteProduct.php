<?php
include "connectDatabase.php";
include "utilFunctions.php";

$message = "";

if (isset($_POST['submit'])) {
    if (!isset($_POST['product']) || $_POST['product'] == "") {
        $message = "You have not selected a product.";
    } else {
        $product_id = (int)$_POST['product'];

        /* double-check that the product is still safe to delete */
        $sqlCheck = "
            SELECT p.product_id
            FROM products p
            LEFT JOIN order_items oi ON p.product_id = oi.product_id
            LEFT JOIN reviews r ON p.product_id = r.product_id
            WHERE p.product_id = $product_id
            GROUP BY p.product_id
            HAVING COUNT(DISTINCT oi.order_item_id) = 0
               AND COUNT(DISTINCT r.review_id) = 0
        ";

        $resultCheck = $conn->query($sqlCheck);

        if ($resultCheck && $resultCheck->num_rows > 0) {
            $sqlDelete = "DELETE FROM products WHERE product_id = $product_id";

            if ($conn->query($sqlDelete) === TRUE) {
                $message = "Product record for product_id=$product_id successfully deleted!";
            } else {
                $message = "Error deleting product: " . $conn->error;
            }
        } else {
            $message = "This product cannot be deleted because it is already used in orders or reviews.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - Delete Product</title>
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
        margin-bottom: 16px;
        color: #2f2018;
    }

    .sectionText {
        font-size: 16px;
        color: #6a5142;
        margin-bottom: 24px;
    }

    .productBlock {
        background-color: #fffaf5;
        border: 1px solid #d9ccbe;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
        min-height: 460px;
    }

    .productPreview {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
        background-color: #f2f2f2;
    }

    .productInfo {
        line-height: 1.8;
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

    .messageBox {
        margin-bottom: 20px;
        border-radius: 8px;
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
        <h1 class="pageTitle">Delete Product</h1>
    </div>

    <div class="sectionBox">
        <form action="deleteProduct.php" method="POST" id="deleteProduct">
            <h2 class="sectionHeading">Delete a Product</h2>
            <p class="sectionText">
                Select a product to delete. Only products with no order history and no reviews are shown below.
            </p>

            <?php
            if ($message != "") {
                echo "<div class='w3-panel w3-pale-yellow w3-border messageBox'>$message</div>";
            }
            ?>

            <label><strong>Product</strong></label>
            <select class="w3-select w3-border" name="product" id="product" required>
                <option value="" disabled selected>Choose Product</option>
                <?php
                $sqlProducts = "
                    SELECT p.product_id, p.product_name
                    FROM products p
                    LEFT JOIN order_items oi ON p.product_id = oi.product_id
                    LEFT JOIN reviews r ON p.product_id = r.product_id
                    GROUP BY p.product_id, p.product_name
                    HAVING COUNT(DISTINCT oi.order_item_id) = 0
                       AND COUNT(DISTINCT r.review_id) = 0
                    ORDER BY p.product_name
                ";

                $resultProducts = $conn->query($sqlProducts);

                if ($resultProducts && $resultProducts->num_rows > 0) {
                    while ($rowProduct = $resultProducts->fetch_assoc()) {
                        $pid = $rowProduct['product_id'];
                        $pname = htmlspecialchars($rowProduct['product_name']);

                        echo "<option value='$pid'>$pid - $pname</option>";
                    }
                }
                ?>
            </select>

            <br><br>
            <input type="submit" name="submit" class="w3-button btnMain" value="Delete Product">
        </form>
    </div>

    <div class="sectionBox">
        <h2 class="sectionHeading">Products Safe to Delete</h2>
        <p class="sectionText">These products are not tied to any order items or reviews.</p>

        <div class="w3-row-padding">
            <?php
            $sqlSafeProducts = "
                SELECT 
                    p.product_id,
                    p.product_name,
                    p.category,
                    p.description,
                    p.price,
                    p.image_path,
                    GROUP_CONCAT(
                        ps.size
                        ORDER BY FIELD(ps.size, 'XXS','XS','S','M','L','XL','XXL')
                        SEPARATOR ', '
                    ) AS sizes
                FROM products p
                LEFT JOIN product_sizes ps ON p.product_id = ps.product_id
                LEFT JOIN order_items oi ON p.product_id = oi.product_id
                LEFT JOIN reviews r ON p.product_id = r.product_id
                GROUP BY p.product_id, p.product_name, p.category, p.description, p.price, p.image_path
                HAVING COUNT(DISTINCT oi.order_item_id) = 0
                   AND COUNT(DISTINCT r.review_id) = 0
                ORDER BY p.product_name
            ";

            $resultSafeProducts = $conn->query($sqlSafeProducts);

            if ($resultSafeProducts && $resultSafeProducts->num_rows > 0) {
                while ($product = $resultSafeProducts->fetch_assoc()) {
                    $product_id = $product['product_id'];
                    $product_name = htmlspecialchars($product['product_name']);
                    $category = htmlspecialchars($product['category']);
                    $description = htmlspecialchars($product['description']);
                    $price = number_format((float)$product['price'], 2);
                    $image_path = htmlspecialchars($product['image_path']);
                    $sizes = htmlspecialchars($product['sizes']);

                    echo "<div class='w3-third'>";
                    echo "<div class='productBlock'>";

                    if ($image_path != "" && file_exists($image_path)) {
                        echo "<img src='$image_path' alt='$product_name' class='productPreview'>";
                    } else {
                        echo "<img src='images/placeholder.jpg' alt='No image available' class='productPreview'>";
                    }

                    echo "<div class='productInfo'>";
                    echo "<strong>Product ID:</strong> $product_id<br>";
                    echo "<strong>Name:</strong> $product_name<br>";
                    echo "<strong>Category:</strong> $category<br>";
                    echo "<strong>Price:</strong> $$price<br>";
                    echo "<strong>Sizes:</strong> $sizes<br>";
                    echo "<strong>Description:</strong> $description";
                    echo "</div>";

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='sectionText'><strong>No products are currently safe to delete.</strong></div>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

</body>
</html>