#!/bin/sh

# create EL2W ports
bash -x ./el2w_create -m vxlan0 -i eth2 -a 192.100.42.20 -v 21 -r hub -n ab -s Earth
bash -x ./el2w_create -m vxlan1 -i eth2 -a 192.100.44.21 -v 31 -r hub -n ab -s Venus
bash -x ./el2w_create -m vxlan2 -i eth2 -a 192.100.46.22 -v 41 -r hub -n ab -s Mars

# install and start layer 2 services
bash -x ./etherate_service start -m etherate -r hub -b ovs-br1
bash -x ./boot_service start -m boot -r hub -b ovs-br1 -I 192.7.7.4 -P 192.7.7.4 -D 192.7.7.4 -R 192.7.7.4 -C 192.7.7.4
