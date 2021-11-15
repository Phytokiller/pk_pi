sudo cp pk_server.service /etc/systemd/system/
sudo cp pk_client_ardbox.service /etc/systemd/system/
sudo cp pk_chromium.service /etc/systemd/system/


sudo systemctl --system daemon-reload
sudo systemctl start pk_server.service
sudo systemctl enable pk_server.service

sudo systemctl start pk_client_ardbox.service
sudo systemctl enable pk_client_ardbox.service

sudo systemctl start pk_chromium.service
sudo systemctl enable chromium.service

