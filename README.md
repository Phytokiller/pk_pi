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
