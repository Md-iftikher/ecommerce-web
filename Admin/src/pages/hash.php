<?php
$new_password = 'nazeef123'; // Replace with your new password
$hash = password_hash($new_password, PASSWORD_BCRYPT);
echo $hash;
?>
