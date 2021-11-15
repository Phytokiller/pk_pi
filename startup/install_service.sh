sudo cp pk_server.service /etc/systemd/system/
sudo cp pk_client_ardbox.service /etc/systemd/system/
sudo cp pk_chromium.service /etc/systemd/system/


sudo systemctl --system daemon-reload

sudo systemctl enable pk_server.service
sudo systemctl restart pk_server.service

sudo systemctl enable pk_client_ardbox.service
sudo systemctl restart pk_client_ardbox.service

sudo systemctl enable pk_chromium.service
sudo systemctl restart pk_chromium.service
