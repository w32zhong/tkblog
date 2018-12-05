#!/bin/bash
if [ "$1" == "-h" ]; then
cat << USAGE
Description:
start blog searchd
Examples:
$0 init (install jieba and whoosh, then chmod)
$0 start
$0 reindex (must after searchd is started)
$0 clear (delete index)
$0 kill
USAGE
exit
fi

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
searchd_path=${DIR}/searchd
searchd_name="searchd.py"
cd $searchd_path

if [ $# -ne 1 ]; then
	echo 'bad arg.' && exit
fi

function kill_searchd() {
	pid_searchd=`ps aux | grep "${searchd_name}" | grep python | awk '{print $2}'`
	kill ${pid_searchd}
	echo "killing PID=${pid_searchd}..."
}

if [ "$1" == "start" ]; then
	echo "[ starting searchd... ]"
	nohup python ./${searchd_name} 0<&- &> searchd.log &
	
elif [ "$1" == "init" ]; then
	pip install whoosh
	pip install jieba

elif [ "$1" == "reindex" ]; then
	curl "http://127.0.0.1/tkblog/resource/search/search_req.php?action=clear"
	python "${searchd_path}/reindex.py"

elif [ "$1" == "clear" ]; then
	curl "http://127.0.0.1/tkblog/resource/search/search_req.php?action=clear"

elif [ "$1" == "kill" ]; then
	kill_searchd
else
	echo 'bad option arg.'
	exit
fi

echo "[ done ]"
