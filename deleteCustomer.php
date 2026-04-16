<?php
include "connectDatabase.php";
include "utilFunctions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Street Boutique - Delete Customer</title>
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

    .customerBlock {
        background-color: #fffaf5;
        border: 1px solid #d9ccbe;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
    }

    .customerInfo {
        margin-bottom: 12px;
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

    .tableHeader {
        background-color: #d8c4b2;
        color: #2f2018;
    }

    .w3-table {
        background-color: #fffdfb;
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
        <h1 class="pageTitle">Delete Customer</h1>
    </div>

    <div class="sectionBox">
        <form method="POST" id="deleteCustomer">
            <h2 class="sectionHeading">Delete a Customer</h2>
            <p class="sectionText">Select a customer to delete. View Current Customer list below.
                <b>NOTE: Only customers with NO ORDERS can be deleted.</b>
            </p>

            <label><strong>Customer</strong></label>
            <select class="w3-select w3-border" name="customer" id="customer" required>
                <option value="" disabled selected>Choose Customer</option>
                <?php
                $sqlCustomers = "SELECT customer_id, firstName, lastName FROM customer ORDER BY lastName, firstName";
                $resultCustomers = $conn->query($sqlCustomers);

                if ($resultCustomers && $resultCustomers->num_rows > 0) {
                    while ($rowCustomer = $resultCustomers->fetch_assoc()) {
                        $cid = $rowCustomer['customer_id'];
                        $fname = $rowCustomer['firstName'];
                        $lname = $rowCustomer['lastName'];

                        echo "<option value='$cid'>$cid - $lname, $fname</option>";
                    }
                }
                ?>
            </select>
            <br>
            <br><input type="submit" name="submit" class="w3-button btnMain" value="Delete Customer">
        </form>
    </div>

    <div class="w3-container">
            <?php
                if(isset($_POST['submit'])) {
                    if(!isset($_POST['customer'])) {
                        echo "<br>You have not selected a customer. Please go back and try again<br>";
                        exit;
                    }
                    
                    $customer_id = $_POST['customer'];

                    include "connectDatabase.php";

                    $sql  = "DELETE ";
                    $sql .= "FROM customer ";
                    $sql .= "WHERE customer_id = '$customer_id' ";

                    if($conn->query($sql) === TRUE) 
                        echo "Customer record for customer_id=$customer_id successfully deleted!<br>";
                    else
                        echo "Error: ".$sql."<br>".$conn->error;
                    
                    $conn->close();

                    # REFRESH CURRENT PAGE TO UPDATE THE DROPDOWN LIST
                    header("Refresh:0");
                }
            ?>
        </div>

    <div class="sectionBox">
        <h2 class="sectionHeading">Current Customers List</h2>

        <?php
        $sqlCustomers = "
            SELECT customer_id, firstName, lastName, address, city, state, zip
            FROM customer
            ORDER BY lastName, firstName
        ";

        $resultCustomers = $conn->query($sqlCustomers);

        if ($resultCustomers && $resultCustomers->num_rows > 0) {
            while ($customer = $resultCustomers->fetch_assoc()) {
                $customer_id = $customer['customer_id'];
                $firstName = htmlspecialchars($customer['firstName']);
                $lastName = htmlspecialchars($customer['lastName']);
                $address = htmlspecialchars($customer['address']);
                $city = htmlspecialchars($customer['city']);
                $state = htmlspecialchars($customer['state']);
                $zip = htmlspecialchars($customer['zip']);

                echo "<div class='customerBlock'>";
                echo "<div class='customerInfo'>";
                echo "<strong>Customer ID:</strong> $customer_id<br>";
                echo "<strong>Name:</strong> $firstName $lastName<br>";
                echo "<strong>Address:</strong> $address, $city, $state $zip";
                echo "</div>";

                $sqlOrderCount = "
                    SELECT COUNT(*) AS total_orders
                    FROM orders
                    WHERE customer_id = $customer_id
                ";
                $resultOrderCount = $conn->query($sqlOrderCount);
                $orderCount = 0;

                if ($resultOrderCount && $resultOrderCount->num_rows > 0) {
                    $rowCount = $resultOrderCount->fetch_assoc();
                    $orderCount = $rowCount['total_orders'];
                }

                echo "<table class='w3-table w3-striped w3-bordered'>";
                echo "<tr class='tableHeader'>";
                echo "<th>Customer ID</th>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "<th>City</th>";
                echo "<th>State</th>";
                echo "<th class='w3-right-align'>Orders</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>$customer_id</td>";
                echo "<td>$firstName</td>";
                echo "<td>$lastName</td>";
                echo "<td>$city</td>";
                echo "<td>$state</td>";
                echo "<td class='w3-right-align'>$orderCount</td>";
                echo "</tr>";

                echo "</table>";
                echo "</div>";
            }
        } else {
            echo "<div class='sectionText'><strong>No customers found.</strong></div>";
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>