<?php
session_start();
session_destroy();
header("Location: ../public/index.php?logout=1");
exit;
