<!-- Load all active users using ajax =============================== -->
<?php
require '../src/config.php';
require '../src/db.php';

$pendingUsers = $conn->query("SELECT * FROM users WHERE VERIFICATION_STATUS ='Success' ");

if ($pendingUsers->num_rows > 0) {
    echo "<table class='table' border='1'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Name</th>";
    echo "<th>Email</th>";
    echo "<th>Verification Status</th>";
    echo "<th>PROFILE IMAGE</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $pendingUsers->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
        echo "<td>" . htmlspecialchars($row['USER_NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($row['EMAIL']) . "</td>";
        echo "<td>" . htmlspecialchars($row['VERIFICATION_STATUS']) . "</td>";
        echo "<td><img src='../uploads/users/" . htmlspecialchars($row['PROFILE_IMG']) . "' alt='Profile Image' width='50' height='50' style='border-radius:50%; margin:0 auto'></td>";
        echo "</tr>";

    }
    echo "</tbody></table>";

} else {
    echo "No active users found.";
}
?>