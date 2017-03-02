<?php
/*
*   Function to run MailChimp API requests via curl
*
*   Based off the code here ( https://github.com/actuallymentor/MailChimp-API-v3.0-PHP-cURL-example )
*/

if (!function_exists('mailchimp_curl')) {
function mailchimp_curl($url, $user_auth, $rest, $input){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // The URL we're using to get/send data
    curl_setopt($ch, CURLOPT_USERPWD, $user_auth); // Add the API authentication
    
    if( $rest == 'POST' ){
        curl_setopt($ch, CURLOPT_POST, true); // Send a post request to the server
    } elseif ( $rest == 'PATCH' ){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); // Send a patch request to the server to update the listing
    } elseif ( $rest == 'PUT'){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Send a put request to the server to update the listing
    } // If POST or PATCH isn't set then we're using a GET request, which is the default
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); // Tell server to expect JSON
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); // Timeout when connecting to the server
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout when retrieving from the server
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // We want to capture the data returned, so set this to true
    curl_setopt($ch, CURLOPT_HEADER, true);  // Get the HTTP headers sent with the data
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // We don't want to force SSL incase a site doesn't use it
    
    if( $rest !== 'GET' ){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input); // Send the actual data
    }


    // Get the response
    $response = curl_exec($ch);
    
    // Check if there's a MailChimp error
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // If there's any curl errors or MailChimp API errors lets show that
    if (curl_errno($ch) || ( $httpcode < 200 || $httpcode >= 300 )  ) {
        $data = 'error';
    } else {
        curl_close($ch);
        // Turn response into stuff we can use
        $data = json_decode( $response, true );
    }



    // Send the data back to the function calling the curl
    return $data;



}
}