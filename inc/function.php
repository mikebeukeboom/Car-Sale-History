<?php

if (isset($_POST['url']) AND !empty($_POST['url']) AND strpos($_POST['url'], 'domeinen') !== false) {
    $htmlpage = $_POST['url'];
    $html = file_get_html($htmlpage);
//    $html = file_get_html('http://www.domeinenrz.nl/catalogus?veilingen=2016-11&from=1&search=polo&cookie_kt=on&show=Toon');
    $secondElements = $html->find('div.split-item-second');
    foreach ($html->find('div.split-item-first') as $key => $element) {

        $item['kavelnummer'] = trim($element->plaintext);
        $item['kavelinfo'] = trim($secondElements[$key]->plaintext);
        $articles[] = $item;

    }
//     Debug

//     print "<pre>";
//     print_r($articles);
//     print "</pre>";

    include("conn.php");

    foreach ($articles as $row) {
        $var_kavel = str_replace('Kavel', '', $row['kavelnummer']);
        $string = $row['kavelinfo'];
        preg_match('/Kenteken\s+([A-Z0-9]{1,3}-[A-Z0-9]{1,3}-[A-Z0-9]{1,3})/', $string, $kenteken);
        preg_match('/toelating\s+([A-Z0-9]{1,2}.[A-Z0-9]{1,2}.[A-Z0-9]{1,4})/', $string, $datum);
        preg_match('/km-stand\s+([a-z0-9]*.[a-z0-9]*)/', $string, $km);
        preg_match('/Automatic/', $string, $transmissie);
        preg_match('/Diesel/', $string, $brandstof_Diesel);
        preg_match('/Benzine/', $string, $brandstof_Benzine);
        preg_match('/([A-Z0-9]{1,2}.[A-Z0-9]{1,3},[A-Z0-9]{1,2})/', $string, $gegund);
        preg_match('/Niet\s+gegund/', $string, $niet_gegund);

//        var_dump($gegund);
        if (!$kenteken[1]) {
            $var_kenteken = "Onbekend";
        } else {
            $var_kenteken = $kenteken[1];
        }
        if (!$transmissie[0]) {
            $var_transmissie = 'schakel';
        } else {
            $var_transmissie = $transmissie[0];
        }

        if (!empty($brandstof_Benzine[0])) {
            $var_brandstof = $brandstof_Benzine[0];
        } elseif (!empty($brandstof_Diesel[0])) {
            $var_brandstof = $brandstof_Diesel[0];
        } else {
            $var_brandstof = "Onbekend";
        }
        $var_km = str_replace('.', '', $km[1]);

        if (!empty($datum[1])) {
            $var_datum = str_replace('.', '/', $datum[1]);
        } else {
            $var_datum = "Onbekend";
        }
        if (!empty($niet_gegund[0])){
            $update_nietgegund = "UPDATE auto_data SET bedrag ='".$niet_gegund[0]."' WHERE kenteken='".$var_kenteken."'";
            if(!$result = $conn->query($update_nietgegund)) print $conn->error;
        }


        $alreadyInDb = "SELECT * from auto_data WHERE kenteken='".$var_kenteken."'";
        if(!$result = $conn->query($alreadyInDb)) print $conn->error;

        $rowkenteken = $result->fetch_array(MYSQLI_ASSOC);
        if ((empty($rowkenteken['kenteken']) OR $rowkenteken['kenteken'] == 'Onbekend') AND $rowkenteken['kmstand'] != $var_km) {
            $sql = "INSERT INTO auto_data (kenteken,kavel,brandstof,transmissie,jaar,kmstand) VALUES ('" . $var_kenteken . "','" .$var_kavel. "','" . $var_brandstof . "','" . $var_transmissie . "','" . $var_datum . "','" . $var_km . "')";

            $insert = "INSERT INTO incase_hardware (sn,machineName,group,subGroup,screenSize,colour,family,modelIdentifier,modelNumber,modelCode,cpuSpeed,ramType,ramSlots,maxRamBySlot,maxRam,ramInserted,assetTag) VALUES ('".$snInfo['Serial']."','".$snInfo['Machine Name']."','".$snInfo['Group']."','".$snInfo['Subgroup']."','".$snInfo['Screen']."','".$snInfo['Colour']."','".$snInfo['Family']."','".$snInfo['Model Identifier']."','".$snInfo['Model Number']."','".$snInfo['Model Code']."','".$snInfo['CPU Speed']."','".$snInfo['RAM Type']."','".$snInfo['RAM Slots']."','".$snInfo['Max RAM by slot']."','".$snInfo['Maximum RAM']."')";

            if (!$result = $conn->query($sql)) print $conn->error;
        }
        if (!empty($gegund[1])){
            $update = "UPDATE auto_data SET verkocht = 1, bedrag = '".$gegund[1]."' WHERE kenteken='".$var_kenteken."'";
            if(!$result = $conn->query($update)) print $conn->error;
        }
    }
    $conn->close();
}

function api($kenteken){
    include("conn.php");
    $array = json_decode(file_get_contents('https://opendata.rdw.nl/resource/m9d7-ebf2.json?kenteken='.str_replace('-','',$kenteken).''), true);
    $cilinders =  $array[0]['aantal_cilinders'];
    $cilinderinhoud = $array[0]['cilinderinhoud'];
    $datum_eerste_afgifte_nederland = $array[0]['datum_eerste_afgifte_nederland'];
    $datum_eerste_toelating = $array[0]['datum_eerste_toelating'];
    $datum_tenaamstelling = $array[0]['datum_tenaamstelling'];
    $eerste_kleur = $array[0]['eerste_kleur'];
    $handelsbenaming = $array[0]['handelsbenaming'];
    $inrichting = $array[0]['inrichting'];
    $tweede_kleur = $array[0]['tweede_kleur'];
    $vervaldatum_apk = $array[0]['vervaldatum_apk'];
    $wacht_op_keuren = $array[0]['wacht_op_keuren'];
    $zuinigheidslabel = $array[0]['zuinigheidslabel'];
    $catalogusprijs = $array[0]['catalogusprijs'];
    $qry = "UPDATE auto_data SET cilinders ='".$cilinders."',cilinderinhoud ='".$cilinderinhoud."',datum_eerste_afgifte_nederland ='".$datum_eerste_afgifte_nederland."',datum_eerste_toelating ='".$datum_eerste_toelating."',datum_tenaamstelling ='".$datum_tenaamstelling."',eerste_kleur ='".$eerste_kleur."',handelsbenaming ='".$handelsbenaming."',inrichting ='".$inrichting."',tweede_kleur ='".$tweede_kleur."',vervaldatum_apk ='".$vervaldatum_apk."',wacht_op_keuren ='".$wacht_op_keuren."',zuinigheidslabel ='".$zuinigheidslabel."',catalogusprijs='".$catalogusprijs."' WHERE kenteken='".$kenteken."'";
    if (!$qry = $conn->query($qry)) print $conn->error;
    $conn->close();
}
function avarage_value($kenteken,$kmstand)
{
    if ($kenteken != 'Onbekend') {
        $kenteken = str_replace('-', '', $kenteken);
        return "http://www.marktplaats.nl/m/auto/auto-verkopen/#/".$kenteken."/".$kmstand;
    }
}
function extraInfo($informatie){
    if(!empty($informatie)){
        #return "<div id='dialog' title='Dialog Title'>".$informatie."Im in a dialog</div>";
        return "informatie.php/?extrainfo=".$informatie;
    }
}
function waarde($kmstand,$bedrag){
    $bedrag = str_replace(".","",$bedrag);
    $bedrag = str_replace(",","",$bedrag) / 100;
    $waarde = $bedrag / $kmstand;
    return round($waarde * 1000,2);
}
?>

