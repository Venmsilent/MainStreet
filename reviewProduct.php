<?php
include "connectDatabase.php";
include "utilFunctions.php";

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$message = "";

if ($product_id <= 0) {
    die("Invalid product.");
}

$sqlProduct = "SELECT product_name FROM products WHERE product_id = $product_id";
$resultProduct = $conn->query($sqlProduct);

if (!$resultProduct || $resultProduct->num_rows == 0) {
    die("Product not found.");
}

$product = $resultProduct->fetch_assoc();
$product_name = htmlspecialchars($product['product_name']);

if (isset($_POST['submitReview'])) {
    $customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    if ($customer_id <= 0 || $rating <= 0 || $rating > 5 || trim($review_text) == "") {
        $message = "Please fill out all review fields correctly.";
    } else {
        $sqlInsert = "INSERT INTO reviews (product_id, customer_id, rating, review_text)
                      VALUES ($product_id, $customer_id, $rating, '$review_text')";

        if ($conn->query($sqlInsert) === TRUE) {
            $message = "Review submitted successfully.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - Review Product</title>
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
        <h1 class="pageTitle">Product Reviews</h1>
    </div>

    <div class="sectionBox">
        <h2 class="sectionHeading">Review: <?php echo $product_name; ?></h2>
        <p class="sectionText">Write a review for this product below.</p>

        <?php
        if ($message != "") {
            echo "<div class='w3-panel w3-pale-yellow w3-border'>$message</div>";
        }
        ?>

        <form method="POST">
            <label>Customer</label>
            <select class="w3-select w3-border" name="customer_id">
                <option value="" disabled selected>Choose Customer</option>
                <?php
                $sqlCustomers = "SELECT customer_id, firstName, lastName FROM customer ORDER BY lastName, firstName";
                $resultCustomers = $conn->query($sqlCustomers);

                if ($resultCustomers && $resultCustomers->num_rows > 0) {
                    while ($rowCustomer = $resultCustomers->fetch_assoc()) {
                        $cid = $rowCustomer['customer_id'];
                        $fname = htmlspecialchars($rowCustomer['firstName']);
                        $lname = htmlspecialchars($rowCustomer['lastName']);

                        echo "<option value='$cid'>$lname, $fname</option>";
                    }
                }
                ?>
            </select>

            <label>Rating</label>
            <select class="w3-select w3-border" name="rating">
                <option value="" disabled selected>Choose Rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>

            <label>Review</label>
            <textarea class="w3-input w3-border" name="review_text" rows="5"></textarea>

            <br>
            <button class="w3-button btnMain" type="submit" name="submitReview">Submit Review</button>
        </form>
    </div>

    <div class="sectionBox">
        <h2 class="sectionHeading">Existing Reviews</h2>

        <?php
        $sqlReviews = "
            SELECT r.rating, r.review_text, c.firstName, c.lastName
            FROM reviews r
            LEFT JOIN customer c ON r.customer_id = c.customer_id
            WHERE r.product_id = $product_id
            ORDER BY r.review_id DESC
        ";

        $resultReviews = $conn->query($sqlReviews);

        if ($resultReviews && $resultReviews->num_rows > 0) {
            while ($review = $resultReviews->fetch_assoc()) {
                $customerName = htmlspecialchars($review['firstName'] . " " . $review['lastName']);
                $rating = (int)$review['rating'];
                $reviewText = htmlspecialchars($review['review_text']);

                echo "<div class='reviewBlock'>";
                echo "<strong>Customer:</strong> $customerName<br>";
                echo "<strong>Rating:</strong> $rating / 5<br>";
                echo "<p>$reviewText</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews yet for this product.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>