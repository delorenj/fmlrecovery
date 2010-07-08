<?php

require_once(dirname(__FILE__)."/../environment.inc");
require_once('fedex-common.php5');

class EpcShippingLabel
//
// This facade class wraps the FedEx Label Creation web service in a simple interface.
//
{

  const SHIP_LABEL = "labels/shipgroundlabel.pdf";
  const SHIP_IMAGE = "labels/shipgroundlabel.png";
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
    if(setDefaults('check'))
    {
      $this->key=setDefaults('key');
      $this->password=setDefaults('password');
      $this->shipAccount=setDefaults('shipaccount');
      $this->meter=setDefaults('meter');
      $this->billAccount=setDefaults('billaccount');
      $this->dutyAccount=setDefaults('dutyaccount');
    }
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
    $this->client = new SoapClient(dirname(__FILE__)."/wsdl/ShipService_v8.wsdl", array('trace' => 1));
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
                                            'DropoffType' => 'REGULAR_PICKUP',
                                            'ServiceType' => 'FEDEX_GROUND',
                                            'PackagingType' => 'YOUR_PACKAGING',
                                            'Shipper' => array('Contact' => array('PersonName' => $data["personName"],
                                                                                  'PhoneNumber' => $data["phoneNumber"]),
                                                               'Address' => array('StreetLines' => array($data["street"]),
                                                                                  'City' => $data["city"],
                                                                                  'StateOrProvinceCode' => $data["state"],
                                                                                  'PostalCode' => $data["zip"],
                                                                                  'CountryCode' => 'US')),
                                            'Recipient' => array('Contact' => array('CompanyName' => 'fmlRecovery',
                                                                                    'PhoneNumber' => '9734408809'),
                                                                 'Address' => array('StreetLines' => array('9 Morris Rd'),
                                                                                    'City' => 'Stanhope',
                                                                                    'StateOrProvinceCode' => 'NJ',
                                                                                    'PostalCode' => '07874',
                                                                                    'CountryCode' => 'US')),
                                            'ShippingChargesPayment' => array('PaymentType' => 'SENDER',
                                                                              'Payor' => array('AccountNumber' => $this->billAccount,
                                                                                               'CountryCode' => 'US')),
                                            'LabelSpecification' => array('LabelFormatType' => 'COMMON2D',
                                                                          'ImageType' => 'PDF',
                                                                          'LabelStockType' => 'PAPER_7X4.75'),
                                            'RateRequestTypes' => array('ACCOUNT'),
                                            'PackageCount' => 1,
                                            'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                            'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '1',
                                                                         'Weight' => array('Value' => $data["weight"], 'Units' => 'LB'),
                                                                         'Dimensions' => array('Length' => $data["L"],
                                                                                               'Width' => $data["W"],
                                                                                               'Height' => $data["H"],
                                                                                               'Units' => 'IN'),
                                                                         'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => $data["customerReference"]),
                                                                                                       '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => $data["transactionNumber"])))));

    fb("Label initialized with shipping data");
  }//End Function: init()

function init_email_label($data) {
  $this->request['WebAuthenticationDetail'] = array('UserCredential' =>
                                                        array('Key' => $this->key, 'Password' => $this->password));
  $this->request['ClientDetail'] = array('AccountNumber' => $this->shipAccount, 'MeterNumber' => $this->meter);
  $this->request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ticket Number '.$data["transactionNumber"].' ***');
  $this->request['Version'] = array('ServiceId' => 'ship', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
  $this->request['RequestedShipment'] = array('ShipTimestamp' => date('c'),
                                              'DropoffType' => 'REGULAR_PICKUP',
                                              'ServiceType' => 'FEDEX_GROUND',
                                              'PackagingType' => 'YOUR_PACKAGING',
                                              'Shipper' => array('Contact' => array('PersonName' => $data["personName"],
                                                                                    'PhoneNumber' => $data["phoneNumber"],
                                                                                    'EMailAddress' => $data["email"]),
                                                                 'Address' => array('StreetLines' => array($data["street"]),
                                                                                    'City' => $data["city"],
                                                                                    'StateOrProvinceCode' => $data["state"],
                                                                                    'PostalCode' => $data["zip"],
                                                                                    'CountryCode' => 'US')),
                                              'Recipient' => array('Contact' => array('CompanyName' => 'fmlRecovery',
                                                                                      'PhoneNumber' => '9734408809',
                                                                                      'EMailAddress' => 'tickets@fmlrecovery.com'),
                                                                   'Address' => array('StreetLines' => array('9 Morris Rd'),
                                                                                      'City' => 'Stanhope',
                                                                                      'StateOrProvinceCode' => 'NJ',
                                                                                      'PostalCode' => '07874',
                                                                                      'CountryCode' => 'US')),
                                              'ShippingChargesPayment' => array('PaymentType' => 'SENDER',
                                                                                'Payor' => array('AccountNumber' => $this->billAccount,
                                                                                                 'CountryCode' => 'US')),
                                              'SpecialServicesRequested' => array('SpecialServiceTypes' => array ('RETURN_SHIPMENT', 'PENDING_SHIPMENT'),
                                                                    'EMailNotificationDetail' => array('PersonalMessage' => 'TEST PERSONAL MESSAGE',
                                                                                                        'Recipients' => array('EMailNotificationRecipientType' => 'RECIPIENT',
                                                                                                        'EMailAddress' => $data["email"],
                                                                                                        'Format' => 'HTML',
                                                                                                        'Localization' => array('LanguageCode' => 'EN', 'LocaleCode' => 'US'))),
                                                                    'ReturnShipmentDetail' => array('ReturnType' => 'PENDING',
                                                                                                    'ReturnEMailDetail' => array('MerchantPhoneNumber' => '973 440 8809')),
                                                                    'PendingShipmentDetail' => array('Type' => 'EMAIL', 'ExpirationDate' => date('Y-m-d'),
                                                                                             'EmailLabelDetail' => array('NotificationEMailAddress' => $data["email"],
                                                                                                                          'NotificationMessage' => 'Thank you for choosing fmlRecovery! You can print and use this FedEx shipping label to send us your damaged media free of charge. '))),
                                              'LabelSpecification' => array('LabelFormatType' => 'COMMON2D',
                                                                            'ImageType' => 'PNG'),
                                              'RateRequestTypes' => array('ACCOUNT','LIST'),
                                              'PackageCount' => 1,
                                              'PackageDetail' => 'INDIVIDUAL_PACKAGES',
                                              'RequestedPackageLineItems' => array('0' => array('SequenceNumber' => '1',
                                                                           'ItemDescription' => $data["mediaSize"].'GB '.$data["mediaType"],
                                                                           'Weight' => array('Value' => $data["weight"], 'Units' => 'LB'),
                                                                           'Dimensions' => array('Length' => $data["L"],
                                                                                                 'Width' => $data["W"],
                                                                                                 'Height' => $data["H"],
                                                                                                 'Units' => 'IN'),
                                                                           'CustomerReferences' => array('0' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => $data["customerReference"]),
                                                                                                         '1' => array('CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => $data["transactionNumber"])))));
  
  }

function create_email_label() {
  try
  {
      $response = $this->client->createPendingShipment($this->request);

      if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
      {
          fb('Url: '.$response->CompletedShipmentDetail->AccessDetail->EmailLabelUrl.Newline);
          fb('User Id: '.$response->CompletedShipmentDetail->AccessDetail->UserId.Newline);
          fb('Password: '.$response->CompletedShipmentDetail->AccessDetail->Password.Newline);
          fb('Tracking Number: '.$response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber.Newline);
//          printSuccess($this->client, $response);
      }
      else
      {
//          printError($client, $response);
      }

      writeToLog($this->client);    // Write to log file

  } catch (SoapFault $exception) {
//      printFault($exception, $client);
        throw new EmailLabelException($exception);
}

  }

  function create(){
    try
    {
        $response = $this->client->createPendingShipment($this->request); // FedEx web service invocation

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