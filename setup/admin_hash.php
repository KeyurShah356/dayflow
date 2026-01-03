<?php


echo "Password hash for 'admin123':\n";
echo password_hash('admin123', PASSWORD_DEFAULT) . "\n";
echo "\nYou can use this hash in the database schema.\n";

