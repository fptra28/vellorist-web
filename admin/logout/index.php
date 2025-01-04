<?php
session_start();
include '../app/config_query.php';
session_destroy();
header("location: $base_url/login.php");
