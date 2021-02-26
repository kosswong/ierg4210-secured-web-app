<?php
$link = mysqli_connect("localhost", "root", "", "test");
$target_dir = "../img/product/o/";

$pid = '';
$catid = '';
$name = '';
$price = '';
$description = '';

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (isset($_GET["action"])) {

    // Add product
    if ($_GET["action"] == 'add' && isset($_POST["name"])) {
        $sql = "INSERT INTO `products` (`pid`, `catid`, `name`, `price`, `description`) VALUES (NULL, '" . $_POST["catid"] . "', '" . $_POST["name"] . "', '" . $_POST["price"] . "', '" . $_POST["description"] . "');";// LIMIT 3
        if ($link->query($sql) === TRUE) {
            echo "New record created successfully";
            if (isset($_FILES["image"])) {
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
                if (isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }

// Check if file already exists
                if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }

// Check file size
                if ($_FILES["image"]["size"] > 500000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

// Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif") {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

// Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    }

    // Delete product
    if ($_GET["action"] == 'delete' && isset($_GET["pid"])) {
        $sql = "DELETE FROM `products` WHERE pid='" . $_GET["pid"] . "'";// LIMIT 3
        if ($link->query($sql) === TRUE) {
            echo "Deleted!";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    }

    // Edit product
    if ($_GET["action"] == 'edit' && isset($_GET["pid"])) {
        $sql = "SELECT * FROM products WHERE pid='" . $_GET["pid"] . "' LIMIT 1";// LIMIT 3
        if ($result = $link->query($sql)) {
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
        $sql = "UPDATE products SET name='" . $_POST["name"] . "', price='" . $_POST["price"] . "', description='" . $_POST["description"] . "' WHERE pid='" . $_POST["pid"] . "'";
        if ($link->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $link->error;
        }
    }

}

// Get categories
$sql = "SELECT * FROM categories";// LIMIT 3
$cat_list = "";
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $cat_list .= "<option value=\"" . $row['catid'] . "\">" . $row['name'] . "</option>";
        }
        mysqli_free_result($result); // Close result set
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Attempt select query execution
$sql = "SELECT * FROM products";// LIMIT 3
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>pid</th>";
        echo "<th>catid</th>";
        echo "<th>name</th>";
        echo "<th>price</th>";
        echo "<th>description</th>";
        echo "<th>operation</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['pid'] . "</td>";
            echo "<td>" . $row['catid'] . "</td>";
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
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


// Close connection
mysqli_close($link);
?>
<br><br><br><br><br><br>


<form action="products.php?action=<?= $pid ? 'save' : 'add' ?>" method="post" enctype="multipart/form-data">
    <?= $pid ? 'Edit' : 'Add' ?> a new product<br>

    <?php if ($pid) { ?>
        <input type="hidden" id="pid" name="pid" value="<?=$pid?>">
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
    <input type="text" id="description" name="description" value="<?= $description ?>"><br><br>

    <label for="image">Select image to upload:</label><br>
    <input type="file" name="image" id="fileToUpload"><br><br>

    <input type="submit" value="Add" name="submit">
</form>
