#!/bin/bash
IP_REMOTE=`tk-echo-bloghost-IP.sh`
which_host="local"
host_ip=''
url=''
year_month="."
curl_extra_arg=""

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
tkblog_blog=${DIR}/blog
upload_path="${tkblog_blog}/${year_month}"
echo "upload ${upload_path}"

if [ "$which_host" == "remote" ]
then
	echo "remote IP: $IP_REMOTE"
	host_ip="$IP_REMOTE"
	# http
	# url="http://${host_ip}/tkblog"

	# https
	url="https://www.approach0.xyz/tkblog"
elif [ "$which_host" == "local" ]
then
	host_ip="127.0.0.1"
	url="http://${host_ip}/tkblog"

	echo "sync local bloghost sql database only..."
	curl_extra_arg="-F 'ifNoTouch=1' -F 'ifNoIndex=1'"
else
	echo "wrong <host> name."
	exit
fi

echo "tkblog URL: ${url} ..."

upload() {
	echo "curl args: ${curl_extra_arg}"
	cmd="curl -F 'action=upload' -F 'files=@${1}' \
	     $curl_extra_arg \
		 "${url}/file_input.php" \
	     2> /dev/null"
	# echo "$cmd"
	bash -c "$cmd"
}

upload_log=~/blog_upload.list

mk_upload_log_from_year_month() {
	find_script=~/blog_find.tmp.sh
	find_path="`cd "${upload_path}" 2> /dev/null && pwd`"
	prune_path0="`cd "${tkblog_blog}/draft" 2> /dev/null && pwd`"
	prune0=''
	if [ $prune_path0 ]
	then
		echo "pruning path 0 exists: ${prune_path0}"
		prune0="-o -path ${prune_path0}"
	fi

	echo "saving ${upload_log}"
	> "${upload_log}"

	echo "saving ${find_script}"
cat << EOF > "${find_script}"
find "$find_path" -type d \( \
-path "/NIL" ${prune0} \) -prune -o -type f >> ${upload_log}
EOF
	chmod +x "${find_script}"
	bash ${find_script}

	if [ "$year_month" == "." ]
	then
		echo 'also include twbook...'
		find "${tkblog_blog}/../twbook" -type f >> ${upload_log}
	fi
}

mk_upload_log_from_year_month

total=`wc "${upload_log}" -l | grep '^[0-9]*' -o`
now=1

cat "${upload_log}" | while read -d $'\n' line
do
	tput setaf 3 # yellow 
	echo "uploading $now/$total..."
	echo "[ $line ]"
	tput sgr0

	res=1
	if [ ! -f "${line}" ]
	then
		echo "not a file, skip..." 
		sleep 3
		continue
	fi
	upload "${line}"
	res=$?
	
	while [ $res != 0 ]
	do
		echo "return err: $res";
		echo 'retry ...';
		upload "${line}"
		res=$?
		sleep 1
	done

	let 'now++';
	sleep 0.01
done

echo '[ generating rss... ]'
curl "${url}/rss_gen.php" | grep generated 
echo '.'
