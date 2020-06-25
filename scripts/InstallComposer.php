<?php

/*
    COMPOSER INSTALL SCRIPT

    This script just contains the instructions given
    in the composer docs on how to install composer
    through PHP.

    Although you don't need to have composer to use
    or work on the project, we recommend it, so that
    you can use great tools like PHP Code Sniffer.
*/

// Download the installer
copy('https://getcomposer.org/installer', './composer-setup.php');

// Verify installer checksum
$checkSum = file_get_contents("https://composer.github.io/installer.sig");

echo ("Downloaded installer with checksum $checkSum" . PHP_EOL);
echo ("Verifying..." . PHP_EOL);

if (hash_file('sha384', './composer-setup.php') === $checkSum) {
    echo 'Installer verified';
} else {
    echo 'Error: Installer corrupt - install halted';
    unlink('composer-setup.php');

    exit(-1);
}
echo PHP_EOL;

echo "Installing Composer..." . PHP_EOL;

// Call PHP on the composer setup script
$output = null;
$installStatus = 0;
exec("php ./composer-setup.php --quiet", $output, $installStatus);

// Finally, delete the setup script
unlink('./composer-setup.php');

echo "Composer has been installed locally. You should be able to call composer.phar on the command line to perform required operations" . PHP_EOL;

// Exit with the status code from the install to tell CI scripts what to think
exit($installStatus);
