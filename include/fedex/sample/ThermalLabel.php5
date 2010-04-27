<?php

// Use the following code snippet for printing FedEx thermal label to a serial port

// Backticks on the following line executes DOS 'mode' command from within PHP
`mode com1: BAUD=9600 PARITY=N data=8 stop=1 xon=off`;
$fp = fopen ("COM1:", "w+");
if (!$fp)
{
    echo "Port not opened.";
}
else 
{
    echo "Port opened.";
    fputs ($fp, ($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));  // Path to label from SOAP response
    fclose ($fp);
}

?>
