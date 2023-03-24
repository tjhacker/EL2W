#!/bin/sh

bash -x ./el2w_create -m vxlan2 -i eth2 -a 192.100.88.10 -v 21 -r spoke -n ab -s Earth
bash -x ./boot_service start -m boot -r spoke -b ovs-br1 -I 192.7.7.10 -P 192.7.7.10 -D 192.7.7.10 -C 192.7.7.4
