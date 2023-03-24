#!/bin/bash

echo "Prepare EL2W Hub..."
mkdir -p mhub/certbundle
cp -r hub/* mhub/certbundle
cp -r common/* mhub/certbundle
mv mhub/certbundle/Vagrantfile mhub/