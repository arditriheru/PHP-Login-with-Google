<?php 
// Google API Configuration
include_once APPPATH . "../vendor/autoload.php";

// Google Client Configuration
$google = json_decode(file_get_contents('google-credentials.json'), true);

$google_client = new Google_Client();
$google_client->setClientId($google['client_id']);
$google_client->setClientSecret($google['client_secret']);
$google_client->setRedirectUri($google['redirect_uris']);

$google_client->addScope('email');
$google_client->addScope('profile');

// if isset code
if (isset($_GET["code"])) {

    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    // if get Google Token
    if (!isset($token["error"])) {

        // POST UIIGateway Token
        $uii_token = $this->curl->getToken();

        // if get GoogleAPI token
        if ($uii_token !== FALSE) {

            // set Google Token
            $this->session->set_userdata('uii_token', $uii_token['access']);
            $google_client->setAccessToken($token['access_token']);
            $this->session->set_userdata('access_token', $token['access_token']);
            $google_service = new Google_Service_Oauth2($google_client);
            $data = $google_service->userinfo->get();

            // if login with uii student account
            if ($data['hd'] == 'students.uii.ac.id') {

                // get id mahasiswa
                $id_mahasiswa   = preg_replace('/@students.uii.ac.id/', '', $data['email']);
            }else{

                echo 'Not UII student!'
            }
        }
    }
}
?>