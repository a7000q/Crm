<?php
error_reporting(0);
include "FaceDetector.php";
$face_detect = new Face_Detector('detection.dat');
$face_detect->face_detect('zp.jpg');
print_r($face_detect->getFace());

?>
