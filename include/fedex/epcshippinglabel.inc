<?php

require_once(dirname(__FILE__)."/../environment.inc");
require_once('fedex-common.php5');

class EpcShippingLabel
{
  //The WSDL is not included with the sample code.
  //Please include and reference in $path_to_wsdl variable.
  //const path_to_wsdl = "include/fedex/wsdl/ShipService_v8.wsdl";
  const SHIP_LABEL = "labels/shipgroundlabel.pdf";  // PDF label file. Change to file-extension .png for creating a PNG label (e.g. shiplabel.png)
  const SHIP_IMAGE = "labels/shipgroundlabel.png";  // PDF label file. Change to file-extension .png for creating a PNG label (e.g. shiplabel.png)
  protected $key;
  protected $password;
  protected $shipAccount;
  protected $meter;
  protected $billAccount;
  protected $dutyAccount;
  protected $client;
  protected $request;

  function __construct(){
    fb("Constructing EpcShippingLabel Object:");
    //Set commonly used variables in fedex-common.php5.  Set check to true.
    if(setDefaults('check'))
    {
      $this->key=setDefaults('key');
      $this->password=setDefaults('password');
      $this->shipAccount=setDefaults('shipaccount');
      $this->meter=setDefaults('meter');
      $this->billAccount=setDefaults('billaccount');
      $this->dutyAccount=setDefaults('dutyaccount');
    }
    //Set commonly used variables below.  Set check to false.
    else
    {
      $this->key='XXX';
      $this->password='YYY';
      $this->shipAccount='XXX';
      $this->meter='XXX';
      $this->billAccount='XXX';
      $this->dutyAccount='XXX';
    }

    ini_set("soap.wsdl_cache_enabled", "0");
    $this->client = new SoapClient(dirname(__FILE__)."/wsdl/ShipService_v8.wsdl", array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
    fb("key=$this->key\nbillAccount=$this->billAccount");
  }//End Constructor()


  function init($data) {
    fb("personName=".$data["personName"]);
    fb("street=".$data["street"]);
    $this->request['WebAuthenticationDetail'] = array('UserCredential' =>
                                                          array('Key' => $this->key, 'Password' => $this->password));
    $this->request['ClientDetail'] = array('AccountNumber' => $this->shipAccount, 'MeterNumber' => $this->meter);
    $this->request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ticket Number '.$data["transactionNumber"].' ***');
    $this->request['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
    $this->request['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                            'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                                            'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                                            'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                                            'Shipper' => array('Contact' => array('PersonName' => $data["personName"],
                                                                                  'PhoneNumber' => $data["phoneNumber"]),
                                                               'Address' => array('StreetLines' => array($data["street"]),
                                                                                  'City' => $data["city"],
                                                                                  'StateOrProvinceCode' => $data["state"],
                                                                                  'PostalCode' => $data["zip"],
                                                                                  'CountryCode' => 'US')),
                                            'Recipient' => array('Contact' => array('CompanyName' => 'etherealPC',
                                                                                    'PhoneNumber' => '9734408809'),
                                                                 'Address' => array('StreetLines' => array('9 Morris Rd'),
                                                                                    'City' => 'Stanhope',
                                                                                    'StateOrProvinceCode' => 'NJ',
                                                                                    'PostalCode' => '07874',
                                                                                    'CountryCode' => 'US')),
                                            'ShippingChargesPayment' => array('PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
                                                                              'Payor' => array('AccountNumber' => $this->billAccount,
                                                                                               'CountryCode' => 'US')),
                                            'LabelSpecification' => array('LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
                                                                          'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
                                                                          'LabelStockType' => 'PAPER_7X4.75'),
                                            'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST
                                            'PackageCount' => 1,
                                            'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                            'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '1',
                                                                         'Weight' => array('Value' => $data["weight"], 'Units' => 'LB'), // valid values LB or KG
                                                                         'Dimensions' => array('Length' => $data["L"],
                                                                                               'Width' => $data["W"],
                                                                                               'Height' => $data["H"],
                                                                                               'Units' => 'IN'),
                                                                         'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => $data["customerReference"]),
                                                                                                       '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => $data["transactionNumber"])))));

    fb("Label initialized with shipping data");
  }//End Function: init()

  function create(){
    try
    {
        $response = $this->client->processShipment($this->request); // FedEx web service invocation

        if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
        {
            // Create PNG or PDF label
            // Set LabelSpecification.ImageType to 'PNG' for generating a PNG label

            $fp = fopen(dirname(__FILE__)."/../../".self::SHIP_LABEL, 'wb');
            fwrite($fp, ($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
            fclose($fp);

            $fp = fopen(dirname(__FILE__)."/../../".self::SHIP_IMAGE, 'wb');
            fwrite($fp, ($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image));
            fclose($fp);
            fb("Shipping label and image were generated");
        }
        else
        {
//            printError($this->client, $response);
        }

    writeToLog($this->client);    // Write to log file

    }
    catch (SoapFault $exception)
    {
        fb("Soap Exception");
//        printFault($exception, $this->client);
    }
  }

}//End Class: EpcShippingLabel
?>