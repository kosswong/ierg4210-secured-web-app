<?php
$link = mysqli_connect("localhost", "root", "", "test");

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (isset($_GET["action"])) {

    if ($_GET["action"] == 'add' && isset($_POST["cat_name"])) {
        $sql = "INSERT INTO `categories` (`catid`, `name`) VALUES (NULL, '" . $_POST["cat_name"] . "');";// LIMIT 3
        if ($link->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    }

    if ($_GET["action"] == 'delete' && isset($_GET["catid"])) {
        $sql = "DELETE FROM `categories` WHERE catid='" . $_GET["catid"] . "'";// LIMIT 3
        if ($link->query($sql) === TRUE) {
            echo "Deleted!";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    }

    if ($_GET["action"] == 'edit' && isset($_GET["catid"])) {
        $sql = "SELECT * FROM categories WHERE catid='".$_GET["catid"]."' LIMIT 1";// LIMIT 3
        if ($result = $link -> query($sql)) {
            while ($row = $result -> fetch_row()) {
                echo 'edit';
                echo '';
                echo '<form action="categories.php?action=save" method="post">';
                echo '<label for="cat_name">Catergoires ID:</label><br>';
                echo '' . $row[0] . '<br><br>';
                echo '<label for="cat_name">Catergoires Name:</label><br>';
                echo '<input type="hidden" id="catid" name="catid" value="' . $row[0] . '">';
                echo '<input type="text" id="cat_name" name="cat_name" value="' . $row[1] . '"><br><br>';
                echo '<input type="submit" value="Save">';
                echo '</form>';
            }
            $result -> free_result();
        }
        exit;
    }

    if ($_GET["action"] == 'save' && isset($_POST["cat_name"])) {
        $sql = "UPDATE categories SET name='".$_POST["cat_name"]."' WHERE catid='".$_POST["catid"]."'";
        if ($link->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $link->error;
        }
    }

}

// Attempt select query execution
$sql = "SELECT * FROM categories";// LIMIT 3
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>catid</th>";
        echo "<th>name</th>";
        echo "<th>operation</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['catid'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>
<a href=\"categories.php?action=edit&catid=" . $row['catid'] . "\">Edit</a>
<a href=\"categories.php?action=delete&catid=" . $row['catid'] . "\">Delete</a>
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

Add:<br>
<form action="categories.php?action=add" method="post">
    <label for="cat_name">Categories Name:</label><br>
    <input type="text" id="cat_name" name="cat_name" value="Test"><br><br>
    <input type="submit" value="Submit">
</form>

<a href='../admin/products.php'>Edit Products</a> |
<a href='../index.php'>Home</a>