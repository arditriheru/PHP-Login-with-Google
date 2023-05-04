<?php 
function getToken()
{
    /* Array Header */
    $data = json_decode(file_get_contents('gateway-credentials'), true);

    $options = array(
        CURLOPT_POST            => 1,
        CURLOPT_HEADER          => 0,
        CURLOPT_URL             => 'https://xapi.uii.ac.id/v1/token/', //URL endpoint
        CURLOPT_FRESH_CONNECT   => 1,
        CURLOPT_RETURNTRANSFER  => 1,
        CURLOPT_FORBID_REUSE    => 1,
        CURLOPT_TIMEOUT         => 80,
        CURLOPT_POSTFIELDS      => http_build_query($data)
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $options));
    $result = json_decode(curl_exec($ch), true);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpcode == 200) {
        return $result;
    } else {
        return FALSE;
    }
}
?>