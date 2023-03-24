#!/bin/bash

USAGE="Syntax: ./make_spoke.sh <Mars|Earth|Venus>"
option=$1
if [ -z $option ] || [ $option == "help" ] || [ $option == "-h" ]; then
  echo $USAGE
  exit 0
fi

if [ $option != "Mars" ] && [ $option != "Earth" ] && [ $option != "Venus" ]; then
  echo $USAGE
  exit 1
fi

echo "Preparing EL2W Spoke $option..."
dir=spoke/$option/
mkdir -p mspoke/certbundle
cp -r common/* mspoke/certbundle
cp $dir/doit.sh mspoke/certbundle
cp $dir/Vagrantfile mspoke
