<?php

// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 8.0.0

require_once('../../../library/fedex-common.php5');


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

define('SHIP_LABEL', 'shipexpresslabel.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)
define('SHIP_CODLABEL', 'CODexpressreturnlabel.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. CODexpressreturnlabel.pdf)

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information


$request['WebAuthenticationDetail'] = array('UserCredential' =>
                                        array('Key' => $key, 'Password' => $password));
$request['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Express Domestic Shipping Request v8 using PHP ***');
$request['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');

$request['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                    'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                    'ServiceType' => 'PRIORITY_OVERNIGHT', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                    'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                    'TotalWeight' => array('Value' => 50.0, 'Units' => 'LB'), // valid values LB and KG
                                    'Shipper' => array('Contact' => array('PersonName' => 'Sender Name',
                                                                          'CompanyName' => 'Sender Company Name',
                                                                          'PhoneNumber' => '9012638716'),
                                                       'Address' => array('StreetLines' => array('Address Line 1'),
                                                                          'City' => 'Austin',
                                                                          'StateOrProvinceCode' => 'TX',
                                                                          'PostalCode' => '73301',
                                                                          'CountryCode' => 'US')),
                                    'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                            'CompanyName' => 'Recipient Company Name',
                                                                            'PhoneNumber' => '9012637906'),
                                                         'Address' => array('StreetLines' => array('Address Line 1'),
                                                                            'City' => 'Herndon',
                                                                            'StateOrProvinceCode' => 'VA',
                                                                            'PostalCode' => '20171',
                                                                            'CountryCode' => 'US'),
                                                                            'Residential' => true),
                                    'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                      'Payor' => array('AccountNumber' => $billAccount,
                                                                                       'CountryCode' => 'US')),
                                    'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                         'CodDetail' => array('CollectionType' => 'ANY'), // ANY, GUARANTEED_FUNDS
                                                                         'CodCollectionAmount' => array('Amount' => 150, 'Currency' => 'USD')),
                                    'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                  'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                  'LabelStockType' => 'PAPER_7X4.75'), 
                                    'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                    'PackageCount' => 1,
                                    'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                    'RequestedPackageLineItems' => array('0' => array('Weight' => array('Value' => 5.0,
                                                                                    'Units' => 'LB'),
                                                                                    'Dimensions' => array('Length' => 20,
                                                                                        'Width' => 20,
                                                                                        'Height' => 10,
                                                                                        'Units' => 'IN'))));


try 
{
    $response = $client->processShipment($request);  // FedEx web service invocation

    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $response);
        
        $fp = fopen(SHIP_CODLABEL, 'wb');   
        fwrite($fp, $response->CompletedShipmentDetail->CodReturnDetail->Label->Parts->Image); //Create COD Return PNG or PDF file
        fclose($fp);
        echo '<a href="./'.SHIP_CODLABEL.'">'.SHIP_CODLABEL.'</a> was generated.'.Newline;

        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PDF' or 'PNG for generating a PDF or a PNG label       
        $fp = fopen(SHIP_LABEL, 'wb');   
        fwrite($fp, $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image); //Create PNG or PDF file
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