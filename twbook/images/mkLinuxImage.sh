#!/bin/bash

rm /root/make_my_linux
touch /root/make_my_linux
if [ ! -e /root/make_my_linux ]
then
	echo 'No permission.'
	exit
fi

echo ---------Make a bootable disk file----------
dd if=/dev/zero of=disk.img bs=1M count=64
mkfs.ext2 -F disk.img
mount disk.img mount_disk -o loop
grub-install --root-directory=`pwd`/mount_disk /dev/loop0 --force

echo ---------Make a ram filesys file----------
RDSIZE=4000
BLKSIZE=1024
dd if=/dev/zero of=ramdisk.img bs=$BLKSIZE count=$RDSIZE
/sbin/mke2fs -F -m 0 -b $BLKSIZE ramdisk.img $RDSIZE # or mkfs.ext2 ramdisk.img
mount ramdisk.img mount_ram -o loop

echo ---------add files to the ram filesys----------
mkdir mount_ram/dev
mkdir mount_ram/proc
mkdir mount_ram/sys
mkdir mount_ram/bin
cp ./busybox mount_ram/bin/

pushd mount_ram/bin
ln -s busybox chroot
popd

cp -a /dev/console mount_ram/dev
cp -a /dev/ram0 mount_ram/dev
cp -a /dev/null mount_ram/dev
cp -a /dev/tty mount_ram/dev
cp -a /dev/tty1 mount_ram/dev
cp -a /dev/tty2 mount_ram/dev

pushd mount_ram
cp -r /home/think/async/busybox-1.19.4/_install/* .
rm ./linuxrc

mkdir -p ./etc/init.d

echo "#!/bin/sh
mount -t proc /proc /proc
mount -t sysfs /sys /sys 
mkdir /my_sda
mount /dev/sda /my_sda
cp -r /bin /my_sda
cp -r /usr /my_sda
cp -r /sbin /my_sda
cp -r /dev /my_sda
mkdir -p /my_sda/etc
mkdir -p /my_sda/proc
mkdir -p /my_sda/sys
mount -o bind /proc /my_sda/proc #lest the chroot jail
mount -o bind /sys /my_sda/sys #lest the chroot jail
for module in \$(cat /my_sda/lib/modules.order)
do
	echo "I am inserting module: \$module"
	insmod "/my_sda/lib/\$module"
	sleep 1
done
chroot /my_sda /MyStartTasks.sh
echo 'if u go here, game over.'" > ./etc/init.d/rcS
chmod +x ./etc/init.d/rcS

popd
echo ---------final initrd file:----------
ls ./mount_ram
umount ./mount_ram

echo ---------compress the ram filesys----------
gzip -9 ./ramdisk.img

echo ---------final disk file:----------
mv ramdisk.img.gz ./mount_disk
cp bzImage ./mount_disk
cp grub.cfg ./mount_disk/boot/grub
cp -r ./MyFiles ./mount_disk
cp -r ./lib ./mount_disk
echo "#!/bin/sh
ifconfig eth0 192.168.1.121 
ifconfig lo 127.0.0.1
route add default gw 192.168.1.1
echo '-----net config-----'
route -n
ifconfig
echo '--------------------'
echo 'hacking tty ...'
setsid cttyhack sh" > ./mount_disk/MyStartTasks.sh
chmod +x ./mount_disk/MyStartTasks.sh
ls ./mount_disk
umount ./mount_disk

echo ---------convey to VBox---------------------
#rm /home/think/disk.VDI
#vboxmanage convertfromraw disk.img /home/think/disk.VDI --format VDI
#chmod 777 /home/think/disk.VDI
#
#rm ./disk.img

echo ---------check mount---------------------
ls mount_*
rm -r mount_ram/*
rm -r mount_disk/*
