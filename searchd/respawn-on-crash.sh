until python3 searchd.py; do
	echo "Server 'myserver' crashed with exit code $?.  Respawning.." >> crash.log
	sleep 1
done
