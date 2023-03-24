<?php
header("Content-type: text/plain");
echo "#!ipxe\n";
echo "imgfree\n";
$proxy = "http://juno-proxy.load:3128";
echo "set http-proxy " . $proxy . "\n";
echo ":selection\n";
echo "menu Loading region: \${net0/user-class}. Select an image to load on this system.\n";
echo "item --key m mars (m) Select Mars loading region\n";
echo "item --key e earth (e) Select Earth loading region\n";
echo "item --key v venus (v) Select Venus loading region\n";
echo "item\n";
echo "item --key t truenas (t) Install TrueNAS server\n";
echo "item --key u truenas_efi (u) Install TrueNAS server via EFI\n";
echo "item --key f freenas (f) Install FreeNAS server\n";
echo "item --key g freenas_efi (g) Install FreeNAS server via EFI\n";
echo "item --key c centos7 (c) Install CentOS7\n";
echo "item --key b centos8 (b) Install CentOS8\n";
echo "item --key d centos8-cached (d) Install CentOS8 from cache\n";
echo "item --key r rocky8 (r) Install Rocky from cache\n";
echo "item --key p pfs (p) Install pfSense\n";
echo "item --key x pfs_efi (x) Install pfSense via EFI\n";
echo "choose target\n";
echo "show target\n";
echo "goto \${target}\n";
echo "exit\n";

echo ":mars\n";
echo "#Mars loading region\n";
echo "set net0/user-class mars\n";
echo "goto selection\n";

echo ":earth\n";
echo "#Earth loading region\n";
echo "set net0/user-class earth\n";
echo "goto selection\n";

echo ":venus\n";
echo "#Venus loading region\n";
echo "set net0/user-class venus\n";
echo "goto selection\n";

echo ":truenas\n";
$post_cmd = "imgfetch http://juno.load/dhcp_opt.php?selection=\${target}\n";
echo $post_cmd;
echo "imgfree dhcp_opt.php\n";
echo "dhcp\n";
echo "sleep 4\n";
echo ":retry_truenas_nfs\n";
echo "imgfetch --timeout 1000000 nfs://192.7.7.4/truenas/boot/pxeboot || goto retry_truenas_nfs\n";
echo "echo Image loaded.\n";
echo "boot pxeboot\n";

echo ":truenas_efi\n";
$post_cmd = "imgfetch http://juno.load/dhcp_opt.php?selection=truenas\n";
echo $post_cmd;
echo "imgfree dhcp_opt.php\n";
echo "dhcp\n";
echo "sleep 4\n";
echo ":retry_truenas_nfs_efi\n";
echo "chain --timeout 1000000 nfs://192.7.7.4/truenas/boot/loader_lua.efi || goto retry_truenas_nfs_efi\n";
echo "echo Image loaded.\n";

echo ":freenas\n";
$post_cmd = "imgfetch http://juno.load/dhcp_opt.php?selection=\${target}\n";
echo $post_cmd;
echo "imgfree dhcp_opt.php\n";
echo "dhcp\n";
echo "sleep 4\n";
echo ":retry_freenas_nfs\n";
echo "imgfetch --timeout 1000000 nfs://192.7.7.4/freenas/boot/pxeboot || goto retry_freenas_nfs\n";
echo "echo Image loaded.\n";
echo "boot pxeboot\n";

echo ":freenas_efi\n";
$post_cmd = "imgfetch http://juno.load/dhcp_opt.php?selection=freenas\n";
echo $post_cmd;
echo "imgfree dhcp_opt.php\n";
echo "dhcp\n";
echo "sleep 4\n";
echo ":retry_freenas_nfs_efi\n";
echo "chain --timeout 1000000 nfs://192.7.7.4/freenas/boot/loader_lua.efi || goto retry_freenas_nfs_efi\n";
echo "echo Image loaded.\n";

echo ":centos7\n";
echo "#CentOS7\n";
echo "dhcp\n";
echo "kernel http://ftp.ussg.iu.edu/linux/centos/7/os/x86_64/images/pxeboot/vmlinuz proxy=" . $proxy . " text repo=http://ftp.ussg.iu.edu/linux/centos/7/os/x86_64 text ks=http://juno.load/ks-centos7.cfg initrd=initrd.img inst.dhcpclass=\${net0/user-class} dhcpclass=\${net0/user-class}\n";
echo "initrd http://ftp.ussg.iu.edu/linux/centos/7/os/x86_64/images/pxeboot/initrd.img\n";
echo "boot\n";

echo ":centos8\n";
echo "#CentOS8\n";
echo "dhcp\n";
echo "kernel http://ftp.ussg.iu.edu/linux/centos/8-stream/BaseOS/x86_64/os/images/pxeboot/vmlinuz proxy=" . $proxy . " inst.text inst.repo=http://ftp.ussg.iu.edu/linux/centos/8-stream/BaseOS/x86_64/os inst.text inst.ks=http://juno.load/ks-centos8.cfg initrd=initrd.img inst.dhcpclass=\${net0/user-class}\n";
echo "initrd http://ftp.ussg.iu.edu/linux/centos/8-stream/BaseOS/x86_64/os/images/pxeboot/initrd.img\n";
echo "boot\n";

echo ":centos8-cached\n";
echo "#CentOS8 from Cache\n";
echo "dhcp\n";
echo "kernel http://juno.load/centos8/x86_64/images/pxeboot/vmlinuz proxy=" . $proxy . " inst.text inst.repo=http://juno.load/centos8/x86_64 inst.text inst.ks=http://juno.load/ks-centos8.cfg initrd=initrd.img inst.dhcpclass=\${net0/user-class} \n";
echo "initrd http://juno.load/centos8/x86_64/images/pxeboot/initrd.img\n";
echo "boot\n";

echo ":rocky8\n";
echo "#Rocky8\n";
echo "dhcp\n";
echo "kernel http://juno.load/rocky/x86_64/images/pxeboot/vmlinuz proxy=" . $proxy . " inst.text inst.repo=http://juno.load/rocky/x86_64 text inst.ks=http://juno.load/ks-rocky.cfg initrd=initrd.img inst.dhcpclass=\${net0/user-class}\n";
echo "initrd http://juno.load/rocky/x86_64/images/pxeboot/initrd.img\n";
echo "boot\n";

echo ":pfs\n";
$post_cmd = "imgfetch http://juno.load/dhcp_opt.php?selection=\${target}\n";
echo $post_cmd;
echo "imgfree dhcp_opt.php\n";
echo "dhcp\n";
echo "sleep 4\n";
echo ":retry_pfs_nfs\n";
echo "imgfetch --timeout 1000000 nfs://192.7.7.4/pfs/boot/pxeboot || goto retry_pfs_nfs\n";
echo "echo Image loaded.\n";
echo "boot pxeboot\n";

echo ":pfs_efi\n";
$post_cmd = "imgfetch http://juno.load/dhcp_opt.php?selection=pfs\n";
echo $post_cmd;
echo "imgfree dhcp_opt.php\n";
echo "dhcp\n";
echo "sleep 4\n";
echo ":retry_pfs_nfs_efi\n";
echo "chain --timeout 1000000 nfs://192.7.7.4/pfs/boot/loader_lua.efi || goto retry_pfs_nfs_efi\n";
echo "echo Image loaded.\n";
