<?php

// Copyright 2009, FedEx Corporation. All rights reserved.

define('TRANSACTIONS_LOG_FILE', 'conf/fedextransactions.log');  // Transactions log file

/**
 *  Print SOAP request and response
 */
define('Newline',"<br />");

function printSuccess($client, $response) {
    echo '<h2>Transaction Successful</h2>';  
    echo "\n";
    printRequestResponse($client);
}
function printRequestResponse($client){
	echo '<h2>Request</h2>' . "\n";
	echo '<pre>' . htmlspecialchars($client->__getLastRequest()). '</pre>';  
	echo "\n";
   
	echo '<h2>Response</h2>'. "\n";
	echo '<pre>' . htmlspecialchars($client->__getLastResponse()). '</pre>';
	echo "\n";
}

/**
 *  Print SOAP Fault
 */  
function printFault($exception, $client) {
    echo '<h2>Fault</h2>' . "<br>\n";                        
    echo "<b>Code:</b>{$exception->faultcode}<br>\n";
    echo "<b>String:</b>{$exception->faultstring}<br>\n";
    writeToLog($client);
}

/**
 * SOAP request/response logging to a file
 */                                  
function writeToLog($client){  
if (!$logfile = fopen(dirname(__FILE__)."/../../".TRANSACTIONS_LOG_FILE, "a"))
{
   error_func("Cannot open " . dirname(__FILE__)."/../../".TRANSACTIONS_LOG_FILE . " file.\n", 0);
   exit(1);
}

fwrite($logfile, sprintf("\r%s:- %s",date("D M j G:i:s T Y"), $client->__getLastRequest(). "\n\n" . $client->__getLastResponse()));
}

//To use these defaults set the check to return true.
function setDefaults($var){
	if($var == 'shipaccount') Return '393961961';
/**
 * If the billaccount and dutyaccount do not match the shipping account
 * the pay type will need to be changed from SENDER in shipping transactions.
 */
	if($var == 'billaccount') Return '393961961';
	if($var == 'dutyaccount') Return '393961961';
	if($var == 'meter') Return '101988886';
	if($var == 'key') Return '09KbRKbKrhEK49FK';
	if($var == 'password') Return 'eHPUpPORZhGoDJAqnnOoLJZYV';
	if($var == 'check') Return true;
}

function printNotifications($notes, $level){
	foreach($notes as $noteKey => $note){
		if(is_string($note)){    
            echo $level . $noteKey . ': ' .$note."<br>";
        }
        else{
        	echo $level . $noteKey."<br>";
        	printNotifications($note, $level."&nbsp;&nbsp;&nbsp;");
        }
	}
}

function printServices($Options){
        foreach ($Options as $optionKey => $option)
        {
        	if(is_string($option)){
				echo $optionKey . ': ' . $option . Newline;
        	}else{           
				printServices($option);
        	}
        }
        echo Newline;
}

function printError($client, $response){
    echo '<h2>Error returned in processing transaction</h2>';
	echo "\n";
	printNotifications($response -> Notifications, ' ');
    printRequestResponse($client, $response);
}

?>
