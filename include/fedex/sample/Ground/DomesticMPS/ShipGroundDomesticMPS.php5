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
define('SHIP_CODMASTERLABEL', 'CODmasterlabel.pdf');    // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. CODmasterlabel.pdf)
define('SHIP_CODCHILDLABEL_1', 'CODchildlabel_1.pdf');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. CODchildlabel_2.pdf)

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$masterRequest['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
$masterRequest['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
$masterRequest['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Domestic MPS Shipping Request - Master using PHP ***');
$masterRequest['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
$masterRequest['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                          'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                          'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                          'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                          'Shipper' => array('Contact' => array('PersonName' => 'Shipper Name',
                                                                                'CompanyName' => 'Company Name',
                                                                                'PhoneNumber' => '9012639431'),
                                                             'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                'City' => 'Collierville',
                                                                                'StateOrProvinceCode' => 'TN',
                                                                                'PostalCode' => '38017',
                                                                                'CountryCode' => 'US')),
                                          'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                                  'CompanyName' => 'Company Name',
                                                                                  'PhoneNumber' => '9012637906'),
                                                               'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                  'City' => 'Barre',
                                                                                  'StateOrProvinceCode' => 'MA',
                                                                                  'PostalCode' => '01006',
                                                                                  'CountryCode' => 'US')),
                                          'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                            'Payor' => array('AccountNumber' => $billAccount,
                                                                                             'CountryCode' => 'US')),
                                          'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                         'CodDetail' => array('CollectionType' => 'ANY')), // ANY, GUARANTEED_FUNDS
                                          'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                        'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                        'LabelStockType' => 'PAPER_7X4.75'), 
                                          'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                          'RateRequestTypes' => array('LIST'), // valid values ACCOUNT and LIST
                                          'PackageCount' => 2,
                                          'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                                                                  
                                          'RequestedPackageLineItems' => array('SequenceNumber' => 1,
                                                                       'InsuredValue' => array('Amount' => 100.00, 'Currency' => 'USD'),
                                                                       'Weight' => array('Value' => 70.0, 'Units' => 'LB'),
                                                                       'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => 'GR4567892'), // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                                                                                                     '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => 'INV4567892'),
                                                                                                     '2' => array('CustomerReferenceType' => 'P_O_NUMBER', 'Value' => 'PO4567892')),
                                                                       'SpecialServicesRequested' => array('CodCollectionAmount' => array('Amount' => 15, 'Currency' => 'USD'))));

try 
{
    
$masterResponse = $client->processShipment($masterRequest); // FedEx web service invocation  

writeToLog($client);    // Write to log file

if ($masterResponse->HighestSeverity != 'FAILURE' && $masterResponse->HighestSeverity != 'ERROR')
{
    printSuccess($client, $masterResponse);

    $fp = fopen(SHIP_CODMASTERLABEL, 'wb');   
    fwrite($fp, $masterResponse->CompletedShipmentDetail->CompletedPackageDetails->CodReturnDetail->Label->Parts->Image); //Create COD Return PNG or PDF file
    fclose($fp);
    echo '<a href="./'.SHIP_CODMASTERLABEL.'">'.SHIP_CODMASTERLABEL.'</a> was generated.'.Newline;

    // Create PNG or PDF label
    // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
    $fp = fopen(SHIP_MASTERLABEL, 'wb');   
    fwrite($fp, ($masterResponse->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
    fclose($fp);
    echo '<a href="./'.SHIP_MASTERLABEL.'">'.SHIP_MASTERLABEL.'</a> was generated. Processing package# 1 ...';   
    
    $childRequest['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
    $childRequest['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
    $childRequest['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Domestic MPS Shipping Request - Child 2 using PHP ***');
    $childRequest['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
    $childRequest['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                                'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                                'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                                'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                                'Shipper' => array('Contact' => array('PersonName' => 'Shipper Name',
                                                                                      'CompanyName' => 'Company Name',
                                                                                      'PhoneNumber' => '9012639431'),
                                                                   'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                      'City' => 'Collierville',
                                                                                      'StateOrProvinceCode' => 'TN',
                                                                                      'PostalCode' => '38017',
                                                                                      'CountryCode' => 'US')),
                                                'Recipient' => array('Contact' => array('PersonName' => 'Recipient Name',
                                                                                        'CompanyName' => 'Recipient Company Name',
                                                                                        'PhoneNumber' => '9012637906'),
                                                                     'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                        'City' => 'Barre',
                                                                                        'StateOrProvinceCode' => 'MA',
                                                                                        'PostalCode' => '01006',
                                                                                        'CountryCode' => 'US')),
                                                'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                  'Payor' => array('AccountNumber' => $billAccount,
                                                                                                   'CountryCode' => 'US')),
                                                'SpecialServicesRequested' => array('SpecialServiceTypes' => array('COD'),
                                                                                 'CodDetail' => array('CollectionType' => 'ANY')), // ANY, GUARANTEED_FUNDS
                                                'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                              'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                              'LabelStockType' => 'PAPER_7X4.75'), 
                                                'MasterTrackingId' => $masterResponse->CompletedShipmentDetail->MasterTrackingId,
                                                'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                                'RateRequestTypes' => array('LIST'), // valid values ACCOUNT and LIST
                                                'PackageCount' => 2,
                                                'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                                                                                                                  
                                                'RequestedPackageLineItems' => array('SequenceNumber' => 2,
                                                                             'InsuredValue' => array('Amount' => 100.0, 'Currency' => 'USD'),
                                                                             'Weight' => array('Value' => 70.0, 'Units' => 'LB'),
                                                                             'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => 'GR4567892'), // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                                                                                                           '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => 'INV4567892'),
                                                                                                           '2' => array('CustomerReferenceType' => 'P_O_NUMBER', 'Value' => 'PO4567892')),
                                                                             'SpecialServicesRequested' => array('CodCollectionAmount' => array('Amount' => 51, 'Currency' => 'USD'))));                                                                                                                                                                                                                                                                
    $childResponse = $client->processShipment($childRequest); // FedEx web service invocation  
    
    writeToLog($client);    // Write to log file
    
    if ($childResponse->HighestSeverity != 'FAILURE' && $childResponse->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $childResponse);

        $fp = fopen(SHIP_CODCHILDLABEL_1, 'wb');   
        fwrite($fp, $childResponse->CompletedShipmentDetail->CompletedPackageDetails->CodReturnDetail->Label->Parts->Image); //Create COD Return PNG or PDF file
        fclose($fp);
        echo '<a href="./'.SHIP_CODCHILDLABEL_1.'">'.SHIP_CODCHILDLABEL_1.'</a> was generated.'.Newline;
       
        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
        $fp = fopen(SHIP_CHILDLABEL_1, 'wb');   
        fwrite($fp, ($childResponse->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
        fclose($fp);
        echo '<a href="./'.SHIP_CHILDLABEL_1.'">'.SHIP_CHILDLABEL_1.'</a> was generated';   
    }
    else
    {
        printError($client, $response);
    }
}
else
{
    echo 'Error in processing master transaction.'. Newline. Newline; 
    foreach ($masterResponse->Notifications as $notification)
    {
            if(is_array($masterResponse -> Notifications))
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

} catch (SoapFault $exception) {
    printFault($exception, $client);
}

?>