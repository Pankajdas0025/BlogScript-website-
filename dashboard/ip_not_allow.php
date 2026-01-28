<?php
    // Simple IP allow list implementation for dashboard access
                        $allowed_ips =
                        [
                        "103.183.227.183", // your real public IP
                        "14.139.238.134",
                        "127.0.0.1",       // localhost IPv4 (for local testing)
                        "::1"              // localhost IPv6 (your ::1 output)
                        ];

                        // Detect client IP (basic)
                        $client_ip = $_SERVER['REMOTE_ADDR'];

                        // If behind proxy (optional), prefer X-Forwarded-For first IP
                        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                        {
                        $client_ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
                        }

                        // Normalize and check ===============================================
                        $client_ip = trim($client_ip);

                        if (!in_array($client_ip, $allowed_ips)) {
                        header("HTTP/1.1 403 Forbidden");
                        die("<h2 style='color:red; text-align:center; margin-top:20%;'>Access Denied</h2>
                        <p style='text-align:center;'>Your IP ($client_ip) is not authorized.</p>");
                        }

?>