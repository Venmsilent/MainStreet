<?php
include "utilFunctions.php";
include "connectDatabase.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - New Product</title>
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

    .btnMain {
        background-color: #9b6845;
        color: white;
        border-radius: 24px;
    }

    .btnMain:hover {
        background-color: #7f5235;
        color: white;
    }

    .w3-select,
    .w3-input {
        border-radius: 8px;
    }

    .sizeGroup label {
        display: inline-block;
        margin-right: 14px;
        margin-bottom: 8px;
    }

    .previewImage {
        margin-top: 10px;
        max-width: 200px;
        border-radius: 10px;
        border: 1px solid #ccc;
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
        <h1 class="pageTitle">Add Product</h1>
    </div>

    <div class="sectionBox">

        <form method="POST" enctype="multipart/form-data" id="productForm">

            <h2 class="sectionHeading">Add a New Product</h2>
            <p class="sectionText">Input product information below.</p>

            <fieldset>
                <label>Product Name</label>
                <input type="text" class="w3-input w3-border" name="product_name">

                <label>Category</label>
                <select class="w3-select w3-border" name="category">
                    <option value="" disabled selected>Choose Category</option>
                    <option value="Tops">Tops</option>
                    <option value="Bottoms">Bottoms</option>
                    <option value="Dresses">Dresses</option>
                    <option value="Outerwear">Outerwear</option>
                    <option value="Shoes">Shoes</option>
                    <option value="Accessories">Accessories</option>
                </select>

                <label>Description</label>
                <input type="text" class="w3-input w3-border" name="description">

                <label>Price</label>
                <input type="text" class="w3-input w3-border" name="price">

                <label>Available Sizes</label>
                <div class="sizeGroup">
                    <label><input type="checkbox" name="sizes[]" value="XXS"> XXS</label>
                    <label><input type="checkbox" name="sizes[]" value="XS"> XS</label>
                    <label><input type="checkbox" name="sizes[]" value="S"> S</label>
                    <label><input type="checkbox" name="sizes[]" value="M"> M</label>
                    <label><input type="checkbox" name="sizes[]" value="L"> L</label>
                    <label><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                    <label><input type="checkbox" name="sizes[]" value="XXL"> XXL</label>
                </div>

                <label>Product Image</label>
                <input type="file" class="w3-input w3-border" name="productImage" accept="image/*">
            </fieldset>

            <br><button class="w3-button btnMain" type="submit" name="addProduct">Add Product</button>
        </form>

        <?php
        if (isset($_POST['addProduct'])) {
            if (
                !isset($_POST['product_name']) || !isset($_POST['category']) ||
                !isset($_POST['description']) || !isset($_POST['price'])
            ) {
                echo "<br>You have not entered all the required fields.<br>";
                exit;
            }

            $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $price = mysqli_real_escape_string($conn, $_POST['price']);
            $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : array();
            $image_path = "";

            if ($product_name == "" || $category == "" || $description == "" || $price == "") {
                echo "<br>You have not entered all the required fields.<br>";
                exit;
            }

            if (count($sizes) == 0) {
                echo "<br>Please select at least one size.<br>";
                exit;
            }

            if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
                $targetDir = "images/";

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = time() . "_" . basename($_FILES["productImage"]["name"]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                    $image_path = mysqli_real_escape_string($conn, $targetFile);
                } else {
                    echo "<br>Image upload failed.<br>";
                }
            }

            $sql = "INSERT INTO products (product_name, category, description, price, image_path)
                    VALUES ('$product_name', '$category', '$description', '$price', '$image_path')";

            if ($conn->query($sql) === TRUE) {
                $product_id = $conn->insert_id;

                foreach ($sizes as $size) {
                    $safeSize = mysqli_real_escape_string($conn, $size);
                    $sqlSize = "INSERT INTO product_sizes (product_id, size)
                                VALUES ($product_id, '$safeSize')";
                    $conn->query($sqlSize);
                }

                echo "<br>";
                echo "<strong>Product created successfully!</strong><br>";
                echo "Product ID: $product_id<br>";
                echo "Product Name: " . htmlspecialchars($product_name) . "<br>";
                echo "Category: " . htmlspecialchars($category) . "<br>";
                echo "Description: " . htmlspecialchars($description) . "<br>";
                echo "Price: $" . number_format((float)$price, 2) . "<br>";
                echo "Sizes: " . htmlspecialchars(implode(", ", $sizes)) . "<br>";
                echo "Image Path: " . htmlspecialchars($image_path) . "<br>";

                if ($image_path != "" && file_exists($image_path)) {
                    echo "<br><img src='$image_path' class='previewImage' alt='Product Image'>";
                }
            } else {
                echo "<br>Error: " . $conn->error . "<br>";
            }
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>