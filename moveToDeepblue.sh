#!/bin/bash
git pull
tar cvf public_html.tar.gz public_html/


ping -c 1 mlik.ra
if [ 1 -eq 0 ] 
then
	scp public_html.tar.gz root@mlik.ra:public_html.tar.gz
	ssh root@mlik.ra '~/unpack.sh'
else
	echo 'local mlik not pingable'
fi


ping -c 1 deepblue.cs.camosun.bc.ca
if [ $? -eq 0 ] 
then
	scp public_html.tar.gz cst166@deepblue.cs.camosun.bc.ca:public_html.tar.gz
	ssh cst166@deepblue.cs.camosun.bc.ca '~/unpack.sh'
else
	echo 'Deepblue not pingable'
fi
