<?php
if (isset($_POST['rdw'])) {
    include('conn.php');
    $qry = "SELECT * from auto_data";
    $result = $conn->query($qry);
    while ($row = $result->fetch_assoc()) {
        if ($row['kenteken'] != Onbekend) {
            api($row['kenteken']);
        }
    }
}
print "<form method='post'> <input type='submit' name='rdw' value='Updaten met RDW gegevens'></form>";
?>