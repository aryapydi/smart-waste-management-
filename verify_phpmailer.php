<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    echo "PHPMailer is successfully installed and available!\n";
    echo "PHPMailer class name: " . get_class($mail) . "\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}
