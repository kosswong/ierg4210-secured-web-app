<?php
require '../inc/config.inc.php';

if (auth_admin() !== false) {
    require 'view/nav.php';
    $db = DB();

    $pid = '';
    $catid = '';
    $name = '';
    $price = '';
    $description = '';
    $categories = [];
    $cat_list = '';

// Get categories
    $sql = "SELECT * FROM categories";
    if ($result = mysqli_query($db, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $categories[$row['catid']] = $row['name'];
                $cat_list .= "<option value=\"" . $row['catid'] . "\">" . $row['name'] . "</option>";
            }
            mysqli_free_result($result); // Close result set
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
    }

    if (isset($_GET["action"])) {

        // Get product name and price
        if ($_GET["action"] == 'api') {
            if (isset($_POST['id'])) {
                header('Content-Type: application/json');
                $sql = "SELECT * FROM products WHERE pid='" . $_POST['id'] . "' LIMIT 1";// LIMIT 3
                if ($result = $db->query($sql)) {
                    while ($row = $result->fetch_row()) {
                        echo json_encode(array('id' => $row[0], 'name' => $row[2], 'price' => $row[3]));
                    }
                    $result->free_result();
                }
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array('msg' => 'Error!'));
                exit;
            }
        }

        // Add product
        if ($_GET["action"] == 'add' && isset($_POST["name"])) {
            $sql = $db->prepare("INSERT INTO `products` (`pid`, `catid`, `name`, `price`, `description`) VALUES (NULL, ?, ?, ?, ?)");
            $sql->bind_param('isds', $_POST["catid"], $_POST["name"], $_POST["price"], $_POST["description"]); // 'i' specifies the variable type => 'integer'
            if ($sql->execute() === TRUE) {
                $insert_id = $sql->insert_id;
                echo "New record created successfully";
                if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
                    $imageFileType = uploadImage($insert_id);
                    if ($imageFileType) {
                        $sql = "UPDATE products SET image='" . $insert_id . '.' . $imageFileType . "' WHERE pid='" . $insert_id . "'";
                        if ($db->query($sql) === TRUE) {
                            echo "Record created successfully";
                        }
                    }
                }
            } else {
                echo "Error: " . $sql . "<br>" . $db->error;
            }
        }

        // Delete product
        if ($_GET["action"] == 'delete' && isset($_GET["pid"])) {
            $sql = "DELETE FROM `products` WHERE pid='" . $_GET["pid"] . "'";// LIMIT 3
            if ($db->query($sql) === TRUE) {
                echo "Deleted!";
            } else {
                echo "Error: " . $sql . "<br>" . $db->error;
            }
        }

        // Edit product
        if ($_GET["action"] == 'edit' && isset($_GET["pid"])) {
            $sql = "SELECT * FROM products WHERE pid='" . $_GET["pid"] . "' LIMIT 1";// LIMIT 3
            if ($result = $db->query($sql)) {
                while ($row = $result->fetch_row()) {
                    $pid = $row[0];
                    $catid = $row[1];
                    $name = $row[2];
                    $price = $row[3];
                    $description = $row[4];
                }
                $result->free_result();
            }
        }

        // Save product
        if ($_GET["action"] == 'save' && isset($_POST["pid"])) {
            $sql = $db->prepare("UPDATE products SET name=?, price=?, description=? WHERE pid=?");
            $sql->bind_param('sdsi', $_POST["name"], $_POST["price"], $_POST["description"], $_POST["pid"]); // 'i' specifies the variable type => 'integer'
            if ($sql->execute() === TRUE) {
                echo "Record updated successfully";
                if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
                    $imageFileType = uploadImage($_POST["pid"]);
                    if ($imageFileType) {
                        $sql = "UPDATE products SET image='" . $_POST["pid"] . '.' . $imageFileType . "' WHERE pid='" . $_POST["pid"] . "'";
                        if ($db->query($sql) === TRUE) {
                            echo "Record updated successfully";
                        }
                    }
                }
            } else {
                echo "Error updating record: " . $db->error;
            }
        }

    }

    if (!isset($_GET["action"]) || isset($_GET["action"]) && $_GET["action"] != 'edit') {
// Attempt select query execution
        $sql = "SELECT * FROM products";// LIMIT 3
        if ($result = mysqli_query($db, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th>pid</th>";
                echo "<th><a href='categories.php'>Category</a></th>";
                echo "<th>name</th>";
                echo "<th>price</th>";
                echo "<th>description</th>";
                echo "<th>operation</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['pid'] . "</td>";
                    echo "<td>" . $categories[$row['catid']] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>
<a href=\"products.php?action=edit&pid=" . $row['pid'] . "\">Edit</a>
<a href=\"products.php?action=delete&pid=" . $row['pid'] . "\">Delete</a>
</td>";
                    echo "</tr>";
                }
                echo "</table>";
                // Close result set
                mysqli_free_result($result);
            } else {
                echo "No records matching your query were found.";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
        }
    }

} else {
    header("Location: http://localhost");
    die();
}
?>
<br><br>
<form action="products.php?action=<?= $pid ? 'save' : 'add' ?>" method="post" enctype="multipart/form-data">
    <?= $pid ? 'Edit' : 'Add' ?> a new product<br>

    <?php if ($pid) { ?>
        <input type="hidden" id="pid" name="pid" value="<?= $pid ?>">
        <label for="name">Product ID:</label><br>
        <?= $pid ? $pid : 'N/A' ?><br><br>
    <?php } ?>

    <label for="name">Product Name:</label><br>
    <input type="text" id="name" name="name" value="<?= $name ?>"><br><br>

    <label for="catid">Product Category:</label><br>
    <select name="catid" id="catid"><?= $cat_list ?></select><br><br>

    <label for="price">Product Price:</label><br>
    <input name="price" id="price" type="number" min="0.00" max="10000.00" step="0.01" value="<?= $price ?>"><br><br>

    <label for="description">Product Description:</label><br>
    <textarea id="description" name="description" rows="4" cols="50"><?= $description ?></textarea><br><br>

    <label for="image">Select image to upload:</label><br>
    <input type="file" name="image" id="fileToUpload"><br><br>

    <input type="submit" value="<?= $pid ? 'Edit' : 'Add' ?>" name="submit">
</form>