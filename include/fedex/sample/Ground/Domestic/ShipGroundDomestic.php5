<?php

// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 8.0.0

require_once('../../../fedex-common.php5');


//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../../../wsdl/ShipService_v8.wsdl";

//Set commonly used variables in fedex-common.php5.  Set check to true.
if(setDefaults('check'))
{
	$key=setDefaults('key');
	$password=setDefaults('password');
	$shipAccount=setDefaults('shipaccount');
	$meter=setDefaults('meter');
	$billAccount=setDefaults('billaccount');
	$dutyAccount=setDefaults('dutyaccount');
}
//Set commonly used variables below.  Set check to false.
else
{
	$key='XXX';
	$password='YYY';
	$shipAccount='XXX';
	$meter='XXX';
	$billAccount='XXX';
	$dutyAccount='XXX';
}

define('SHIP_LABEL', 'shipgroundlabel.pdf');  // PDF label file. Change to file-extension .png for creating a PNG label (e.g. shiplabel.png)
define('SHIP_CODLABEL', 'CODgroundreturnlabel.pdf');  // PDF label file. Change to file-extension ..png for creating a PNG label (e.g. CODgroundreturnlabel.png)

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
$request['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Domestic Shipping Request v8 using PHP ***');
$request['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
$request['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                        'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                        'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                        'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                        'Shipper' => array('Contact' => array('CompanyName' => 'Sender Company Name',
                                                                              'PhoneNumber' => '0805522713'),
                                                           'Address' => array('StreetLines' => array('Address Line 1'),
                                                                              'City' => 'Collierville',
                                                                              'StateOrProvinceCode' => 'TN',
                                                                              'PostalCode' => '38017',
                                                                              'CountryCode' => 'US')),
                                        'Recipient' => array('Contact' => array('CompanyName' => 'Recipient Company Name',
                                                                                'PhoneNumber' => '9012637906'),
                                                             'Address' => array('StreetLines' => array('W 34th Street'),
                                                                                'City' => 'Austin',
                                                                                'StateOrProvinceCode' => 'TX',
                                                                                'PostalCode' => '78705',
                                                                                'CountryCode' => 'US')),
                                        'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                          'Payor' => array('AccountNumber' => $billAccount,
                                                                                           'CountryCode' => 'US')),
                                        'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                             'CodDetail' => array('CollectionType' => 'ANY')), // ANY, GUARANTEED_FUNDS
                                        'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                      'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                      'LabelStockType' => 'PAPER_7X4.75'), 
                                        /* Thermal Label */
                                        /*
                                        'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                      'ImageType' => 'EPL2', // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                      'LabelStockType' => 'STOCK_4X6.75_LEADING_DOC_TAB',
                                                                      'LabelPrintingOrientation' => 'TOP_EDGE_OF_TEXT_FIRST'),
                                        */
                                        'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                        'PackageCount' => 1,
                                        'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                        
                                        'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '1',   
                                                                     'Weight' => array('Value' => 50.0, 'Units' => 'LB'), // valid values LB or KG
                                                                     'Dimensions' => array('Length' => 108,
                                                                                           'Width' => 5,
                                                                                           'Height' => 5,
                                                                                           'Units' => 'IN'), // valid values IN or CM
                                                                     'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => 'GR4567892'), // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                                                                                                   '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => 'INV4567892'),
                                                                                                   '2' => array('CustomerReferenceType' => 'P_O_NUMBER', 'Value' => 'PO4567892')),
                                                                     'SpecialServicesRequested' => array('CodCollectionAmount' => array('Amount' => 150, 'Currency' => 'USD'))))); // ANY, GUARANTEED_FUNDS
                                                                                                                           
try 
{
    $response = $client->processShipment($request); // FedEx web service invocation  

    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $response);

        $fp = fopen(SHIP_CODLABEL, 'wb');   
        fwrite($fp, $response->CompletedShipmentDetail->CompletedPackageDetails->CodReturnDetail->Label->Parts->Image); //Create COD Return PNG or PDF file
        fclose($fp);
        echo '<a href="./'.SHIP_CODLABEL.'">'.SHIP_CODLABEL.'</a> was generated.'.Newline;
        
        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PNG' for generating a PNG label
    
        $fp = fopen(SHIP_LABEL, 'wb');   
        fwrite($fp, ($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
        fclose($fp);
        echo '<a href="./'.SHIP_LABEL.'">'.SHIP_LABEL.'</a> was generated.'; 
    }
    else
    {
        printError($client, $response);
    }

writeToLog($client);    // Write to log file

} catch (SoapFault $exception) {
    printFault($exception, $client);
}
?>