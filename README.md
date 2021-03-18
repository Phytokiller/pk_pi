# raspberry

## Configuration

### SSH

1. Enter `sudo raspi-config` in a terminal window.
2. Select Interfacing Options.
3. Navigate to and select SSH.
4. Choose Yes.
5. Select Ok.
6. Choose Finish.

### Touch screen

Afin d'utiliser le port USB-C pour le touch screen, ajouter la ligne suivante à la fin du fichier /boot/config.txt

    dtoverlay=dwc2,dr_mode=host
    
Le port USB-C est maintenant en host mode. L'alimentation se fait desormais via les ports GPIO.


## Installation

### Vim

    sudo apt install vim

### Webserver

#### Apache

    sudo apt update
    sudo apt upgrade
    sudo apt update
    sudo apt install apache2
    sudo chown -R pi:www-data /var/www/html/
    sudo chmod -R 770 /var/www/html/
        
#### PHP

    sudo apt install php php-mbstring

#### MySQL

L'utilisateur sera `root` et le mot de passe sera `Phyt0K!ller`

    sudo apt install mariadb-server php-mysql

    sudo mysql --user=root
    DROP USER 'root'@'localhost';
    CREATE USER 'root'@'localhost' IDENTIFIED BY 'Phyt0K!ller';
    GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
    exit
    
Création de la base phytokiller :

    mysql --user=root --password='Phyt0K!ller'
    CREATE DATABASE phytokiller;

#### Composer

    cd
    wget -O composer-setup.php https://getcomposer.org/installer
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm -rf composer-setup.php
    sudo composer self-update
