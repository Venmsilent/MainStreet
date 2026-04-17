<?php
include "utilFunctions.php";
include "connectDatabase.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - Home</title>
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
        <h1 class="pageTitle">Home</h1>
    </div>

    <div class="sectionBox">
       <p class = "pageTitle"><b>Welcome to MainStreet Boutique!</b></p><br>

       <p class = "pageTitle">For new Customers:</p><br>

        Please click on the <b>Add Customer</b> button to enter your 
        customer information. This will be used to track your products and the amount of orders 
        you have made.<br>

        <p>If you want to make sure you put in the correct customer information, please go to <b>View Customers</b>
        to find your customer ID, name and address.</p>

        <p>If you notice you accidently entered inaccurate information, please go to <b>Delete Customers</b>
        before purchasing as customers with no orders are the only ones available for deletion.</p><br><br>

        <p class = "pageTitle">To Create an Order:</p><br>

        <p>CLick on <b>Add Order</b> to view the products available for purchase. Make sure you select your 
        customer name first before selecting product items. At <b>Add Products</b>, you can add up your product
        items and review the amount of money you need to pay, the size of each product, and the quantity of the product
        being purchased in the order. Once you are done, please click <b>Finish Order</b> to
        submit your purchase.</p><br>

        <p>To see your order, go to <b>View Orders</b>, where you can find your order ID, customer name
        order date, your total payment, and lastly your order status. If your staus says <b>"Pending"</b>, go back and 
        finsish your order and make sure it says <b>"Completed"</b> instead. When viewing your order, you
        can see the product name, category, and description, quantity size, price, and cost.</p>

        <p class = "pageTitle">Products:</p><br>

        <p>If you want to view our clothing options, click on <b>View Products</b> to see our available categories, sizes, and
        prices. If you want to write or look at a review, go to the bottom of the product of your choice and select <b>"Write / View Reviews"</b>.
        You will select your customer name, rate our product from 1-5, and finally write your thoughts bout the product's quality. If there is a review,
        you should see one under <b>"Existing Reviews"</b>.</p><br>

        <p>If you want to request a new product to our system, please go to the <b>Add Products</b> button.
        You will need to create an appropriate product name, category that fits well the most,
        description, price, size that can range from XXS to XXL, and lastly a product image. Please make sure your
        image is in high quality as it will be used as a reference fro production. When you are sure you have everything ready,
        click on <b>Add Product</b>.</p>

        <p>Do you have a product you want to be removed? Head to <b>Delete Product</b>
        to chose a product to delete. Please be aware that products with no order history and Reviews
        are the only ones available to delete. You can see these items under <b>"Products Safe to Delete"</b>.</p><br>

        <p>If you would like to know more about our boutique, clisk on the <b>About</b> button
        for more details. Have a good day!</p>


    </div>
</div>
</body>
</html>
