<?php
include "utilFunctions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - New Customer</title>
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
        <h1 class="pageTitle">Add Customer</h1>
    </div>

    <div class="sectionBox">

        <form method="POST" id="customerForm">

            <h2 class="sectionHeading">Add a New Customer</h2>
            <p class="sectionText">Input customer information below.</p>

            <fieldset>
                <label>First Name</label>
                <input type="text" class="w3-input w3-border" name="fName">

                <label>Last Name</label>
                <input type="text" class="w3-input w3-border" name="lName">

                <label>Address</label>
                <input type="text" class="w3-input w3-border" name="address">

                <label>City</label>
                <input type="text" class="w3-input w3-border" name="city">

                <label>State</label>
                <input type="text" class="w3-input w3-border" name="state">

                <label>ZIP</label>
                <input type="text" class="w3-input w3-border" name="zip">
            </fieldset>

            <br><button class="w3-button btnMain" type="submit" name="addCustomer">Add Customer</button>
        </form>

        <?php
            if(isset($_POST['addCustomer'])) {
                if(!isset($_POST['fName']) || !isset($_POST['lName']) || !isset($_POST['address']) ||!isset($_POST['city']) ||!isset($_POST['state']) || !isset($_POST['zip'])) {
                    echo "<br>You have not entered all the required fields.<br>";
                    echo "Please go back and try again.<br>";

                    exit;
                }

                include "connectDatabase.php";

                # CREATE SHORT VARIABLE NAMES
                $fName = mysqli_real_escape_string($conn, $_POST['fName']);
                $lName = mysqli_real_escape_string($conn, $_POST['lName']);
                $address = mysqli_real_escape_string($conn, $_POST['address']);
                $city = mysqli_real_escape_string($conn, $_POST['city']);
                $state = mysqli_real_escape_string($conn, $_POST['state']);
                $zip = mysqli_real_escape_string($conn, $_POST['zip']);
                
                # CREATE THE SQL STRING
                $sql = "INSERT INTO customer (firstName, lastName, address, city, state, zip) VALUES ('$fName','$lName', '$address', '$city', '$state', '$zip')";

                if($conn->query($sql) === TRUE) {
                    $customer_id = $conn->insert_id;
                    echo "<br>";
                    echo "<strong>Customer created successfully!</strong><br>";
                    echo "Customer id: $customer_id<br>";
                    echo "First Name: $fName<br>";
                    echo "Last Name: $lName<br>";
                    echo "Address: $address<br>";
                    echo "City: $city<br>";
                    echo "State: $state<br>";
                    echo "ZIP: $zip<br>";
                }

                $conn->close();
            }
        ?>
    </div>
</div>

</body>
</html>
