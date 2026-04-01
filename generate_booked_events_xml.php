<?php
header('Content-Type: text/xml'); // tells browser it's XML

include 'db_connect.php'; // your DB connection

$xml = new DOMDocument('1.0', 'UTF-8');
$root = $xml->createElement('booked_events');
$xml->appendChild($root);

$sql = "SELECT * FROM registrations";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    $event = $xml->createElement('event');

    foreach($row as $key => $value){
        $child = $xml->createElement($key, htmlspecialchars($value));
        $event->appendChild($child);
    }

    $root->appendChild($event);
}

$xml->formatOutput = true;
echo $xml->saveXML(); // output XML
