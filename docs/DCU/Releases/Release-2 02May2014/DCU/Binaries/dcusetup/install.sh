#!/bin/sh

PROJECT=sitesync
SERVICE_CONF=dcu.conf

echo "############### Installing libraries.."
sudo apt-get -y update
echo "Installing PYTHON library..."
sudo apt-get -y install python-dev*
echo "Installing SSL library..."
sudo apt-get -y install libssl0.9.7*
echo "Installing XML library..."
sudo apt-get -y install libexpat1-dev*
echo "Installing JSON library..."
sudo apt-get -y install libjson0 libjson0-dev
echo "Copy the executable folder to /home/$PROJECT/"
sudo cp $PROJECT /home/$PROJECT/ -fr
echo "Change the permissions of the folder and all its files"
sudo chmod -R 777 /home/$PROJECT/$PROJECT
sudo chown -R root /home/$PROJECT/$PROJECT
sudo chgrp -R root /home/$PROJECT/$PROJECT
echo "Copy and change the service config file"
sudo cp -fr $SERVICE_CONF /etc/init/
sudo chown root /etc/init/$SERVICE_CONF
sudo chgrp root /etc/init/$SERVICE_CONF
echo "Copy default XML files"
sudo mkdir /cfg
sudo cp *.xml /cfg/
echo "Change permission of config files"
sudo chmod -R 777 /cfg
sudo chown -R root /cfg
sudo chgrp -R root /cfg
echo "--------------------------------------"

