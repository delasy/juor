<?php

/**
 * Domain availability (ajax)
 */

$R = $_GET;
$d = (isset($R['domain']) && $R['domain']) ? $R['domain'] : false;
$ext = (isset($R['extension']) && $R['extension']) ? $R['extension'] : false;

$msg = array(
    'no_domain' => 'No domain specified',
    'no_extension' => 'No domain extension specified',
    'not_available' => 'This domain is not available',
    'available' => 'This domain is available'
);

function build_response ($s, $t = 'error') {
    global $d;
    if ($t !== 'success' && $t !== 'error') $t = 'error';

    $a = array();
    $a['message'] = $s;
    $a['response'] = $t;
    $a['domain'] = $d;
    return json_encode($a);
}

header('Content-Type: application/json');

if (!$d) {
    exit(build_response($msg['no_domain']));
} else if (!$ext) {
    exit(build_response($msg['no_extension']));
} else if (substr($d, 0, 1) === '-' || substr($d, strlen($d) - 1) === '-') {
    exit(build_response($msg['not_available']));
}

$d .= '.' . $ext;
$dns1 = @dns_get_record($d, DNS_NS);

if (!empty($dns1)) {
    exit(build_response($msg['not_available']));
}

$dns2 = @dns_get_record($d, DNS_NS);

if (empty($dns2)) {
    exit(build_response($msg['available'], 'success'));
} else {
    exit(build_response($msg['not_available']));
}

