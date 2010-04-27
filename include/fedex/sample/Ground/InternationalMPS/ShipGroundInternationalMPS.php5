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

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$masterRequest['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password)); 
$masterRequest['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
$masterRequest['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground International MPS Shipping Request - Master using PHP ***');
$masterRequest['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
$masterRequest['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                          'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                          'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                          'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                          'Shipper' => array('Contact' => array('CompanyName' => 'Shipper Company Name',
                                                                                'PhoneNumber' => '0805522713'),
                                                             'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                'City' => 'Collierville',
                                                                                'StateOrProvinceCode' => 'TN',
                                                                                'PostalCode' => '38017',
                                                                                'CountryCode' => 'US')),
                                          'Recipient' => array('Contact' => array('CompanyName' => 'Recipient Company Name',
                                                                                  'PhoneNumber' => '9012637906'),
                                                               'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                  'City' => 'Toronto',
                                                                                  'StateOrProvinceCode' => 'ON',
                                                                                  'PostalCode' => 'M1M 1M1',
                                                                                  'CountryCode' => 'CA')),
                                          'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                            'Payor' => array('AccountNumber' => $billAccount,
                                                                                             'CountryCode' => 'US')),
                                          'InternationalDetail' => array('DutiesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                                  'Payor' => array('AccountNumber' => $dutyAccount,
                                                                                                                   'CountryCode' => 'CA')),
                                                                         'DocumentContent' => 'DOCUMENTS_ONLY',
                                                                         'TermsOfSale' => 'CFR_OR_CPT', // valid values CFR_OR_CPT, CIF_OR_CIP, DDP, DDU, EXW and FOB_OR_FCA
                                                                         'CustomsValue' => array('Amount' => 100.00, 'Currency' => 'USD'),
                                                                         'Commodities' => array('0' => array('NumberOfPieces' => 1,
                                                                                                             'Description' => 'Books',
                                                                                                             'CountryOfManufacture' => 'US',
                                                                                                             'Weight' => array('Value' => 1.0, 'Units' => 'LB'), // valid values LB and KG
                                                                                                             'Quantity' => 1,
                                                                                                             'QuantityUnits' => 'EA',
                                                                                                             'UnitPrice' => array('Amount' => 1.000000, 'Currency' => 'USD'),
                                                                                                             'CustomsValue' => array('Amount' => 100.000000, 'Currency' => 'USD'))),
                                                                   'ExportDetail' => array('B13AFilingOption' => 'FILED_ELECTRONICALLY')),                                                                                                       
                                          'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                  'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                  'LabelStockType' => 'PAPER_7X4.75'), 
                                          'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST     
										  'PackageCount' => 2,
                                          'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                                                                  
                                          'RequestedPackageLineItems' => array('SequenceNumber' => 1,
                                                                       'Weight' => array('Value' => 40.0, 'Units' => 'LB'),
                                                                       'Dimensions' => array('Length' => 108, 'Width' => 5, 'Height' => 5, 'Units' => 'IN'),
                                                                       'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => 'GR4567892'), // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                                                                                                     '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => 'INV4567892'),
                                                                                                     '2' => array('CustomerReferenceType' => 'P_O_NUMBER', 'Value' => 'PO4567892'))));

try
{
        
$masterResponse = $client->processShipment($masterRequest); // FedEx web service invocation

writeToLog($client);    // Write to log file    
    
if ($masterResponse->HighestSeverity != 'FAILURE' && $masterResponse->HighestSeverity != 'ERROR') // check if the master response was a SUCCESS and send a child request
{
    printSuccess($client, $masterResponse);
    
    echo 'Generating label ...'. Newline;
    // Create PNG or PDF label
    // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
    $fp = fopen(SHIP_MASTERLABEL, 'wb');   
    fwrite($fp, ($masterResponse->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
    fclose($fp);
    echo 'Label <a href="./'.SHIP_MASTERLABEL.'">'.SHIP_MASTERLABEL.'</a> was generated. Processing package# 1 ...';
    
    $childRequest['WebAuthenticationDetail'] = array('UserCredential' =>
                                                      array('Key' => $key, 'Password' => $password));
    $childRequest['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
    $childRequest['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground International MPS Shipping Request - Child using PHP ***');
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
                                                                                     'CompanyName' => 'Company Name',
                                                                                     'PhoneNumber' => '9012637906'),
                                                                  'Address' => array('StreetLines' => array('Address Line 1'),
                                                                                     'City' => 'Toronto',
                                                                                     'StateOrProvinceCode' => 'ON',
                                                                                     'PostalCode' => 'M1M 1M1',
                                                                                     'CountryCode' => 'CA',
                                                                                     'Residential' => false)),
                                             'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                                'Payor' => array('AccountNumber' => $billAccount,
                                                                                                 'CountryCode' => 'US')),
                                             'InternationalDetail' => array('DutiesPayment' => array('PaymentType' => 'SENDER',
                                                                                                     'Payor' => array('AccountNumber' => $dutyAccount,
                                                                                                                      'CountryCode' => 'CA')),
                                                                            'DocumentContent' => 'DOCUMENTS_ONLY',
                                                                            'TermsOfSale' => 'CFR_OR_CPT', // valid values CFR_OR_CPT, CIF_OR_CIP, DDP, DDU, EXW and FOB_OR_FCA
                                                                            'CustomsValue' => array('Amount' => 100.00, 'Currency' => 'USD'),
                                                                            'Commodities' => array('0' => array('NumberOfPieces' => 1,
                                                                                                                'Description' => 'Books',
                                                                                                                'CountryOfManufacture' => 'US',
                                                                                                                'Weight' => array('Value' => 1.0, 'Units' => 'LB'), // valid values LB or KG
                                                                                                                'Quantity' => 1,
                                                                                                                'QuantityUnits' => 'EA',
                                                                                                                'UnitPrice' => array('Amount' => 1.000000, 'Currency' => 'USD'),
                                                                                                                'CustomsValue' => array('Amount' => 100.000000, 'Currency' => 'USD'))),
                                                                            'ExportDetail' => array('B13AFilingOption' => 'FILED_ELECTRONICALLY')),  
                                             'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                            'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                            'LabelStockType' => 'PAPER_7X4.75'), 
                                             'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
    										 'MasterTrackingId' => $masterResponse->CompletedShipmentDetail->MasterTrackingId,
                                             'PackageCount' => 2,
                                             'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                                                                     
                                             'RequestedPackageLineItems' => array('SequenceNumber' => 2,
                                                                          'Weight' => array('Value' => 20.0, 'Units' => 'LB')), // valid values LB and KG
                                                                          'Dimensions' => array('Length' => 108, 'Width' => 5, 'Height' => 5, 'Units' => 'IN'), // valid values IN and CM
                                                                          'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => 'GR4567892'), // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                                                                                                        '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => 'INV4567892'),
                                                                                                        '2' => array('CustomerReferenceType' => 'P_O_NUMBER', 'Value' => 'PO4567892')));

    $childResponse = $client->processShipment($childRequest); // FedEx web service invocation

    writeToLog($client);    // Write to log file    
    
    if ($childResponse->HighestSeverity != 'FAILURE' && $childResponse->HighestSeverity != 'ERROR')
    {
        printSuccess($client, $childResponse);
        // Create PNG or PDF label
        // Set LabelSpecification.ImageType to 'PDF' for generating a PDF label
        $fp = fopen(SHIP_CHILDLABEL_1, 'wb');   
        fwrite($fp, $childResponse->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
        fclose($fp);
        echo 'Label <a href="./'.SHIP_CHILDLABEL_1.'">'.SHIP_CHILDLABEL_1.'</a> was generated.';
        
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