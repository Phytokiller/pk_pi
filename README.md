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

    sudo apt install mariadb-server php-mysql
