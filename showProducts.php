<?php
include "connectDatabase.php";
include "utilFunctions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - Show Products</title>
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
        margin-bottom: 8px;
        color: #2f2018;
    }

    .sectionText {
        font-size: 16px;
        color: #6a5142;
        margin-bottom: 24px;
    }

    .productCard {
        border: 1px solid #e7ddd2;
        background-color: #fffaf5;
        padding: 15px;
        margin-bottom: 20px;
        min-height: 470px;
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
        <h1 class="pageTitle">View Products</h1>
    </div>

    <div class="sectionBox">
        <h2 class="sectionHeading">View Available Products</h2>
        <p class="sectionText">Browse all products currently in the catalog.</p>

        <div class="w3-row-padding">
            <?php
            $sqlProducts = "
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
                GROUP BY p.product_id, p.product_name, p.category, p.description, p.price, p.image_path
                ORDER BY p.product_id
            ";

            $resultProducts = $conn->query($sqlProducts);

            if ($resultProducts && $resultProducts->num_rows > 0) {
                while ($row = $resultProducts->fetch_assoc()) {
                    $product_id = $row['product_id'];
                    $product_name = htmlspecialchars($row['product_name']);
                    $category = htmlspecialchars($row['category']);
                    $description = htmlspecialchars($row['description']);
                    $price = number_format((float)$row['price'], 2);
                    $image_path = htmlspecialchars($row['image_path']);
                    $sizes = htmlspecialchars($row['sizes']);

                    echo "<div class='w3-third'>";
                    echo "<div class='productCard'>";

                    if ($image_path != "" && file_exists($image_path)) {
                        echo "<img src='$image_path' alt='$product_name'>";
                    } else {
                        echo "<img src='images/placeholder.jpg' alt='No image available'>";
                    }

                    echo "<h4>$product_name</h4>";
                    echo "<p><strong>Category:</strong> $category</p>";
                    echo "<p><strong>Price:</strong> $$price</p>";
                    echo "<p><strong>Sizes:</strong> $sizes</p>";
                    echo "<p>$description</p>";
                    echo "<p><a href='reviewProduct.php?product_id=$product_id'>Write / View Reviews</a></p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

</body>
</html>