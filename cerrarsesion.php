<?php

// Start session
session_start();
// Destroy session
session_destroy();
// redirect to index.php
header("location:index.php");

?>