# raspberry

> **ATTENTION**: Les écrans avec pi intégré ont une distribution spéciale ne pas faire de mise à jour.

## Configuration

### SSH

1. Enter `sudo raspi-config` in a terminal window.
2. Select Interfacing Options.
3. Navigate to and select SSH.
4. Choose Yes.
5. Select Ok.
6. Choose Finish.

### VNC VIEWER

Pour utiliser VNC sans avoir à brancher un écran physique sur la pi :
1. sudo raspi-config
2. Display Options
3. D1 Resolution
5. DMT Mode 82 1920x1080 60Hz 16:9

### Touch screen (valable sur les anciens écrans)

Afin d'utiliser le port USB-C pour le touch screen, ajouter la ligne suivante à la fin du fichier /boot/config.txt

    dtoverlay=dwc2,dr_mode=host
    
Le port USB-C est maintenant en host mode. L'alimentation se fait desormais via les ports GPIO.

### Hostname

1. Enter `sudo raspi-config`.
2. Select `1. System Options`.
3. Select `S4 Hostname`
4. Type `phytokiller`

## Installation

```
sudo apt-get update
```

### Vim (optional)

```
sudo apt install vim
```

### Webserver

#### NGINX & PHP
```
sudo apt-get install nginx
sudo apt install php8.0-fpm
```

#### Composer

    cd
    wget -O composer-setup.php https://getcomposer.org/installer
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm -rf composer-setup.php
    sudo composer self-update
    
#### Node & NPM

    wget -qO- https://deb.nodesource.com/setup_14.x | sudo -E bash -
    sudo apt-get install -y nodejs
    npm update -g
