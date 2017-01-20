<?php
    $past = time() - 100;
    // This makes the time in the past to destroy the cookie
    setcookie(ID_i_Conquest, gone, $past);
    setcookie(Key_i_Conquest, gone, $past);
    header("Location: index.php");
?>