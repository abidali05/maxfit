<?php
function get_browser_name($userAgent) {
    if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
    elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR/') !== false) return 'Opera';
    elseif (strpos($userAgent, 'Edge') !== false) return 'Edge';
    elseif (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
    elseif (strpos($userAgent, 'Safari') !== false) return 'Safari';
    elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident/7') !== false) return 'Internet Explorer';
    return 'Unknown';
}

function get_os_name($userAgent) {
    if (preg_match('/linux/i', $userAgent)) return 'Linux';
    elseif (preg_match('/macintosh|mac os x/i', $userAgent)) return 'Mac';
    elseif (preg_match('/windows|win32/i', $userAgent)) return 'Windows';
    return 'Unknown';
}

?>