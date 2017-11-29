<?php

include ('conn.php');

$latest = $conn->query("SELECT date_inserted from auto_data ORDER BY id DESC LIMIT 1");
$date = $latest->fetch_row();

$result = $conn->query("SELECT * FROM auto_data where zuinigheidslabel='A' OR  zuinigheidslabel ='B'");
?>
<table  class='table table-striped table-bordered table_hover' id='autos' cellspacing='0' width='100%'>
    <thead>
    <tr>
        <th>Kavel Nummer</th>
        <th>Type</th>
        <th>Brandstof</th>
        <th>Kenteken</th>
        <th>Transmissie</th>
        <th>Km-stand</th>
        <th>Invoerdatum</th>
        <th>Gegund</th>
        <th>Zuinigheids Label</th>
        <th>Extra Info</th>
        <th>Waarde berekenen</th>
        <th>Cata</th>
    </tr>
    </thead>
    <tbody>
<?php
    while ($row = $result->fetch_assoc())
    {
        $type = (!empty($row['handelsbenaming'])?$row['handelsbenaming'] : 'empty');
        if (empty($row['bedrag'])){
        print "<tr style='font-weight: bolder;color: red;'><td>" . $row['kavel'] . "</td><td>".$type."</td><td>" . $row['brandstof'] . "</td><td>". $row['kenteken']. "</td><td>". $row['transmissie']. "</td><td>" .$row['kmstand'] . "</td><td>".$row['jaar'] ."</td></td><td>".$row['bedrag'] ."</td><td>".$row['zuinigheidslabel']."</td><td><a href='".extraInfo($row['id']) ."'<button> Extra info </button></td><td><a href='".avarage_value($row['kenteken'],$row['kmstand']) ."'<button> > </button></td><td>".$row['catalogusprijs']."</td></tr>";
        }
        elseif ($row['date_inserted'] == $date[0]){
            print "<tr style='font-weight: bolder;background-color: orange;'><td>" . $row['kavel'] . "</td><td>".$type."</td><td>" . $row['brandstof'] . "</td><td>". $row['kenteken']. "</td><td>". $row['transmissie']. "</td><td>" .$row['kmstand'] . "</td><td>".$row['jaar'] ."</td></td><td>".$row['bedrag'] ."</td><td>".$row['zuinigheidslabel']."</td><td><a href='".extraInfo($row['id']) ."'<button> Extra info </button></td><td><a href='".avarage_value($row['kenteken'],$row['kmstand']) ."'<button> > </button></td><td>".$row['catalogusprijs']."</td></tr>";
        }
        else{
            print "<tr><td>" . $row['kavel'] . "</td><td>". $type. "</td><td>" . $row['brandstof'] . "</td><td>". $row['kenteken']. "</td><td>". $row['transmissie']. "</td><td>" .$row['kmstand'] . "</td><td>".$row['jaar'] ."</td></td><td>".$row['bedrag'] ."</td><td>".$row['zuinigheidslabel']."</td><td><a href='".extraInfo($row['id']) ."'<button> Extra info </button></td><td><a href='".avarage_value($row['kenteken'],$row['kmstand']) ."'<button> > </button></a></td><td>".$row['catalogusprijs']."</td></tr>";
        }
    }
$conn->close();
?>
    </tbody>
    <tfood>
        <th>Kavel Nummer</th>
        <th>Type</th><th>Brandstof</th>
        <th>Kenteken</th>
        <th>Transmissie</th>
        <th>Km-stand</th>
        <th>Invoerdatum</th>
        <th>Gegund</th>
        <th>Zuinigheids Label</th>
        <th>extra</th>
        <th>Waarde berkenen</th>
        <th>Cata</th>
    </tfood>
</table>



