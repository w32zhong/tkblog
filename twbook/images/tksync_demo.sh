#!/bin/bash

function check_dir()
{
if [ ! -d $1 ]
then
	echo "can not find dir : $1 " && exit 
fi
}

function shadow_rm()
{
cd $shadow_dir
find ./ -type $1 -print0 | while read -d $'\0' file
do
	if [ ! $2 "$work_dir/$file" ]
	then
		#rm -r -f -i "$shadow_dir/$file"
		trash "$shadow_dir/$file" # make sure u have installed trash-cli
	fi
done
}

function shadow_update()
{
work_dir=$1 
shadow_dir=$2
echo 'shadow rm dirs ...'
shadow_rm d -d dir
echo 'shadow rm files ...'
shadow_rm f -e file
echo 'update files ...'
cp -r -u -v -P "$work_dir"/* "$shadow_dir"
}

#config values
PC_DESK_DIR=/home/think/Desktop
PC_DESK_CLOUD_DIR=$PC_DESK_DIR/lib
PC_SYNC_DIR=/home/think/tksync
PC_TWBOOK_SYNC_DIR=$PC_SYNC_DIR/cloud/twbook
CLOUD_DIR=/home/think/YUNIO
U_SYNC_DIR=/media/KINGSTON/tksync
FTP_MOUNT_DIR=/home/think

if [ "$1" == "twbook" ]
then
	TWBOOK_FTP_MOUNT_NAME="MountWebTwbookFTP"
	echo "twbook ftp sync to pc_sync_twbook..."
	check_dir $PC_TWBOOK_SYNC_DIR
	cd $FTP_MOUNT_DIR
	mkdir -p $TWBOOK_FTP_MOUNT_NAME
	
	
	if [ "$(df | grep "curlftpfs")" != "" ]
	then
		echo "ftp is already mounted."
	else
		curlftpfs ftp://blabla.gotoftp1.com/wwwroot/ \
		$TWBOOK_FTP_MOUNT_NAME \
		-o user="usr:psswd"
	fi

	ls $TWBOOK_FTP_MOUNT_NAME
	cp -r -u -v "$TWBOOK_FTP_MOUNT_NAME"/* "$PC_TWBOOK_SYNC_DIR"
	
	find "$PC_TWBOOK_SYNC_DIR" -type d -exec chmod +x {} \; 
	# to enable enter dirs, so we can cp 
	
	#umount $TWBOOK_FTP_MOUNT_NAME
	exit
fi

if [ "$1" != "" ]
then
	echo 'custom sync...'
	check_dir $1 
	check_dir $2
	shadow_update $1 $2
	exit
fi

echo 'tkcync...'
check_dir $PC_DESK_DIR
check_dir $PC_DESK_CLOUD_DIR
check_dir $PC_SYNC_DIR
check_dir $CLOUD_DIR

echo 'cync desktop to pc_sync...'
shadow_update $PC_DESK_DIR $PC_SYNC_DIR/desktop0
shadow_update $PC_DESK_CLOUD_DIR $PC_SYNC_DIR/cloud/cache

echo 'cync pc_sync_cloud to cloud...'
shadow_update $PC_SYNC_DIR/cloud $CLOUD_DIR 

check_dir $U_SYNC_DIR
echo 'cync pc_sync to U disk...'
shadow_update $PC_SYNC_DIR $U_SYNC_DIR
