# raspberry

> **ATTENTION**: Les écrans Chipsee avec pi intégré ont une distribution spéciale. **Ne pas faire de mise à jour.**

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

### Clock
Insérer la pile (CR1220)
1. `sudo apt-get -y remove fake-hwclock`
2. To set current date : `sudo timedatectl set-time '2021:12:30 09:48:00'`
3. To set rtc from current date : `sudo hwclock -w`
4. To set date from rtc : `sudo hwclock -s`
5. Automatisation de la date depuis RTC : `sudo nano /etc/rc.local` : add  `sudo hwclock -s`
6. Utile : `timedatectl`

## Installation

### Webserver

```
sudo apt update
```

Do you want to accept these changes and continue updating from this repository? [y/N]
```
yes
```

#### NGINX & PHP
```
sudo apt install -y lsb-release apt-transport-https ca-certificates wget && sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
```

```
sudo apt update
```

```
sudo apt-get install nginx php8.0-fpm php-xml php-curl php-mbstring php-sqlite3 -y
```

Tester l'installation :
```
php -v
```
> PHP 8.0.11 (cli) (built: Sep 23 2021 22:03:11) ( NTS )
Copyright (c) The PHP Group
Zend Engine v4.0.11, Copyright (c) Zend Technologies
    with Zend OPcache v8.0.11, Copyright (c), by Zend Technologies

#### Composer

    cd
    wget -O composer-setup.php https://getcomposer.org/installer
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm -rf composer-setup.php
    sudo composer self-update
    
#### Node & NPM

    sudo apt install nodejs -y

#### Permissions

    sudo groupadd www-data
    sudo gpasswd -a pi www-data
    sudo chown -R pi:www-data /var/www
    sudo chmod -R 775 /var/www
    
#### First install

    cd /var/www/html
    git clone https://github.com/Phytokiller/pk_pi.git
    ln -s /var/www/html/pk_pi pk_pi
    cd pk_pi/wwww
    composer install --no-interaction --prefer-dist --optimize-autoloader
