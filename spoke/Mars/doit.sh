#!/bin/sh

bash -x ./el2w_create -m vxlan2 -i eth2 -a 192.100.88.10 -v 41 -r spoke -n ab -s Mars
bash -x ./boot_service start -m boot -r spoke -b ovs-br1 -I 192.7.7.12 -P 192.7.7.12 -D 192.7.7.12 -C 192.7.7.4
