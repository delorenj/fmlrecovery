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

define('SHIP_MASTERLABEL', 'shipmasterlabel.pdf');    // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)
define('SHIP_CHILDLABEL_1', 'shipchildlabel_1.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)
define('SHIP_CHILDLABEL_2', 'shipchildlabel_2.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

try 
{

$masterRequest['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password)); 
$masterRequest['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
$masterRequest['TransactionDetail'] = array('CustomerTransactionId' => '*** Express Domestic Shipping Request - Master using PHP ***');
$masterRequest['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
$masterRequest['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                              'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                              'ServiceType' => 'PRIORITY_OVERNIGHT', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...s
                                              'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                              'TotalWeight' => array('Value' => 50.0, 'Units' => 'LB'), // valid values LB and KG
                                              'Shipper' => array('Contact' => array('PersonName' => 'Sender Name',
                                                                                    'CompanyName' => 'Company Name',
                                                                                    'PhoneNumber' => '0805522713'),
                                                                 'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                    'City' => 'Austin',
                                                                                    'StateOrProvinceCode' => 'TX',
                                                                                    'PostalCode' => '73301',
                                                                                    'CountryCode' => 'US')),
                                              'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                                      'CompanyName' => 'Company Name',
                                                                                      'PhoneNumber' => '9012637906'),
                                                                   'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                      'City' => 'Herndon',
                                                                                      'StateOrProvinceCode' => 'VA',
                                                                                      'PostalCode' => '20171',
                                                                                      'CountryCode' => 'US')),
                                              'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                'Payor' => array('AccountNumber' => $billAccount,
                                                                                                 'CountryCode' => 'US')),
                                              'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                                  'CodDetail' => array('CollectionType' => 'ANY'), // ANY, GUARANTEED_FUNDS
                                                                                  'CodCollectionAmount' => array('Amount' => 150, 'Currency' => 'USD')),
                                              'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                            'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                            'LabelStockType' => 'PAPER_7X4.75'), 
                                              'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LISTs
                                              'PackageCount' => 3,              
                                              'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                              'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '1',
                                                                                        'Weight' => array('Value' => 2.0,
                                                                                        'Units' => 'LB'),
                                                                                        'Dimensions' => array('Length' => 10,
                                                                                            'Width' => 10,
                                                                                            'Height' => 3,
                                                                                            'Units' => 'IN'))));
                                                                                        
$masterResponse = $client->processShipment($masterRequest);  // FedEx web service invocation for master label

writeToLog($client);    // Write to log file

if ($masterResponse->HighestSeverity != 'FAILURE' && $masterResponse->HighestSeverity != 'ERROR')
{
    printSuccess($client, $masterResponse);
    
    echo 'Generating label ...'. Newline;
    // Create PNG or PDF label
    // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
    $fp = fopen(SHIP_MASTERLABEL, 'wb');   
    fwrite($fp, $masterResponse->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
    fclose($fp);
    echo 'Label <a href="./'.SHIP_MASTERLABEL.'">'.SHIP_MASTERLABEL.'</a> was generated. Processing package#1 ...';
      
    $childRequest1['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
    $childRequest1['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
    $childRequest1['TransactionDetail'] = array('CustomerTransactionId' => '*** Express Domestic Shipping Request - Child 2 using PHP ***');
    $childRequest1['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
    $childRequest1['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                                 'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                                 'ServiceType' => 'PRIORITY_OVERNIGHT', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...s
                                                 'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                                 'TotalWeight' => array('Value' => 50.0, 'Units' => 'LB'), // valid values LB and KG
                                                 'Shipper' => array('Contact' => array('PersonName' => 'Sender Name',
                                                                                       'CompanyName' => 'Company Name',
                                                                                       'PhoneNumber' => '0805522713'),
                                                                    'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                       'City' => 'Austin',
                                                                                       'StateOrProvinceCode' => 'TX',
                                                                                       'PostalCode' => '73301',
                                                                                       'CountryCode' => 'US')),
                                                 'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                                         'CompanyName' => 'Company Name',
                                                                                         'PhoneNumber' => '9012637906'),
                                                                      'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                         'City' => 'Herndon',
                                                                                         'StateOrProvinceCode' => 'VA',
                                                                                         'PostalCode' => '20171',
                                                                                         'CountryCode' => 'US')),
                                                 'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                   'Payor' => array('AccountNumber' => $billAccount,
                                                                                                    'CountryCode' => 'US')),
                                                 'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                                      'CodDetail' => array('CollectionType' => 'ANY'), // ANY, GUARANTEED_FUNDS
                                                                                      'CodCollectionAmount' => array('Amount' => 15, 'Currency' => 'USD')),
                                                 'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                               'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                               'LabelStockType' => 'PAPER_7X4.75'), 
                                                 'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                                 'PackageCount' => 3,
                                                 'MasterTrackingId' => $masterResponse->CompletedShipmentDetail->MasterTrackingId,
                                                 'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                                 'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '2',
                                                                                            'Weight' => array('Value' => 2.0,
                                                                                            'Units' => 'LB'),
                                                                                            'Dimensions' => array('Length' => 20,
                                                                                                'Width' => 20,
                                                                                                'Height' => 3,
                                                                                                'Units' => 'IN'))));

    $childResponse1 = $client->processShipment($childRequest1);  // FedEx web service invocation  for child label #1
    
    writeToLog($client);    // Write to log file
    
    if ($childResponse1->HighestSeverity != 'FAILURE' && $childResponse1->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $childResponse1);
        
        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
        $fp = fopen(SHIP_CHILDLABEL_1, 'wb');   
        fwrite($fp, $childResponse1->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
        fclose($fp);
        echo 'Label <a href="./'.SHIP_CHILDLABEL_1.'">'.SHIP_CHILDLABEL_1.'</a> was generated. Processing package#2 ...';   
        
    }
    else
    {
        echo '<h3>Errors in processing transaction that generates package label #1.</h3>'. "\n";
        foreach ($childResponse1->Notifications as $notification)
        {
            if(is_array($childResponse1 -> Notifications))
            {              
               echo $notification -> Severity;
               echo ': ';           
               echo $notification -> Message . Newline;
            }
            else
            {
                echo $notification . Newline;
            }
        }
    }
    
    $childRequest2['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
    $childRequest2['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
    $childRequest2['TransactionDetail'] = array('CustomerTransactionId' => '*** Express Domestic Shipping Request - Child 3 using PHP ***');
    $childRequest2['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
    $childRequest2['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                                 'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                                 'ServiceType' => 'PRIORITY_OVERNIGHT', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...s
                                                 'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                                 'TotalWeight' => array('Value' => 50.0, 'Units' => 'LB'), // valid values LB and KG
                                                 'Shipper' => array('Contact' => array('PersonName' => 'Sender Name',
                                                                                       'CompanyName' => 'Company Name',
                                                                                       'PhoneNumber' => '0805522713'),
                                                                    'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                       'City' => 'Austin',
                                                                                       'StateOrProvinceCode' => 'TX',
                                                                                       'PostalCode' => '73301',
                                                                                       'CountryCode' => 'US')),
                                                 'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                                         'CompanyName' => 'Company Name',
                                                                                         'PhoneNumber' => '9012637906'),
                                                                      'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                         'City' => 'Herndon',
                                                                                         'StateOrProvinceCode' => 'VA',
                                                                                         'PostalCode' => '20171',
                                                                                         'CountryCode' => 'US')),
                                                 'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                   'Payor' => array('AccountNumber' => $billAccount,
                                                                                                    'CountryCode' => 'US')),
                                                 'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                                      'CodDetail' => array('CollectionType' => 'GUARANTEED_FUNDS'), // ANY, GUARANTEED_FUNDS
                                                                                      'CodCollectionAmount' => array('Amount' => 79, 'Currency' => 'USD')),
                                                 'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                               'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                               'LabelStockType' => 'PAPER_7X4.75'), 
                                                 'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                                 'PackageCount' => 3,
                                                 'MasterTrackingId' => $masterResponse->CompletedShipmentDetail->MasterTrackingId,                                                 'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                                 'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '3',
                                                                                            'Weight' => array('Value' => 2.0,
                                                                                            'Units' => 'LB'),
                                                                                            'Dimensions' => array('Length' => 30,
                                                                                                'Width' => 30,
                                                                                                'Height' => 3,
                                                                                                'Units' => 'IN'))));

    $childResponse2 = $client->processShipment($childRequest2);  // FedEx web service invocation for child label #2
    
    writeToLog($client);    // Write to log file
    
    if ($childResponse2->HighestSeverity != 'FAILURE' && $childResponse2->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $childResponse2);
        
        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
        $fp = fopen(SHIP_CHILDLABEL_2, 'wb');   
        fwrite($fp, $childResponse2->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
        fclose($fp);
        echo 'Label <a href="./'.SHIP_CHILDLABEL_2.'">'.SHIP_CHILDLABEL_2.'</a> was generated.';
        
    }
    else
    {
        echo '<h3>Errors in processing transaction that generates package label #2.</h3>'. "\n";
        foreach ($childResponse2->Notifications as $notification)
        {
            if(is_array($childResponse2 -> Notifications))
            {              
               echo $notification -> Severity;
               echo ': ';           
               echo $notification -> Message . Newline;
            }
            else
            {
                echo $notification . Newline;
            }
        }
    }    
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