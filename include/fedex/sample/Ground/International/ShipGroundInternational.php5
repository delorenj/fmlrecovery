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

define('SHIP_LABEL', 'shipgroundlabel.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
$request['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground International Shipping Request v7 using PHP ***');
$request['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
$request['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                    'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                    'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                    'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                    'Shipper' => array('Contact' => array('PersonName' => 'Sender Name',
                                                                          'CompanyName' => 'Company Name',
                                                                          'PhoneNumber' => '0805522713'),
                                                       'Address' => array('StreetLines' => array('Address Line 1'),
                                                                          'City' => 'Memphis',
                                                                          'StateOrProvinceCode' => 'TN',
                                                                          'PostalCode' => '38110',
                                                                          'CountryCode' => 'US')),
                                    'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                            'CompanyName' => 'Company Name',
                                                                            'PhoneNumber' => '9012637906'),
                                                         'Address' => array('StreetLines' => array('Address Line 1'),
                                                                            'City' => 'Richmond',
                                                                            'StateOrProvinceCode' => 'BC',
                                                                            'PostalCode' => 'V7C4V4',
                                                                            'CountryCode' => 'CA',
                                                                            'Residential' => false)),
                                    'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                      'Payor' => array('AccountNumber' => $billAccount,
                                                                                       'CountryCode' => 'US')),
                                    'InternationalDetail' => array('DutiesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                            'Payor' => array('AccountNumber' => $dutyAccount,
                                                                                            'CountryCode' => 'CA')),
                                                                   'DocumentContent' => 'DOCUMENTS_ONLY',                                                                                            
                                                                   'CustomsValue' => array('Amount' => 100.0, 'Currency' => 'USD'),
                                                                   'Commodities' => array('0' => array('NumberOfPieces' => 1,
                                                                                                       'Description' => 'Books',
                                                                                                       'CountryOfManufacture' => 'US',
                                                                                                       'Weight' => array('Value' => 1.0, 'Units' => 'LB'),
                                                                                                       'Quantity' => 1,
                                                                                                       'QuantityUnits' => 'EA',
                                                                                                       'UnitPrice' => array('Amount' => 1.000000, 'Currency' => 'USD'),
                                                                                                       'CustomsValue' => array('Amount' => 100.000000, 'Currency' => 'USD'))),
                                                                   'ExportDetail' => array('B13AFilingOption' => 'FILED_ELECTRONICALLY')),                                                                                                       
                                    'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                  'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                  'LabelStockType' => 'PAPER_7X4.75'), 
                                    'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                    'PackageCount' => 1,
                                    'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                        
                                    'RequestedPackageLineItems' => array('SequenceNumber' => 1,
                                                                 'Weight' => array('Value' => 20.0, 'Units' => 'LB')), // valid values LB and KG
                                                                 'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => 'TC007_07_PT1_ST01_PK01_SNDUS_RCPCA_POS')));

try
{
    $response = $client->processShipment($request); // FedEx web service invocation

    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $response);

        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
        $fp = fopen(SHIP_LABEL, 'wb');   
        fwrite($fp, ($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
        fclose($fp);
        echo 'Label <a href="./'.SHIP_LABEL.'">'.SHIP_LABEL.'</a> was generated.';            
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