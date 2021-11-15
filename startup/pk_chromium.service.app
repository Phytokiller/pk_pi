[Unit]
Description=Browser for phytokiller

[Service]
# We need this to make sure that chromium is opened in the right X server session
Environment="DISPLAY=:0"
Environment="XAUTHORITY=/home/pi/.Xauthority"
ExecStart=chromium-browser --kiosk --app=phytokiller.local
WorkingDirectory=/home/pi/fever
Restart=always
RestartSec=10
StandardOutput=syslog
StandardError=syslog
SyslogIdentifier=browser-control-service
User=pi
Group=pi

[Install]
WantedBy=graphical.target