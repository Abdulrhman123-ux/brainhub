<?php
session_start();
session_destroy();
header("Location: /brainhub/brainhub/admin/login.php");
exit;
