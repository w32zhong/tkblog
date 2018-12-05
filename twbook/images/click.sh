#!/bin/bash
encode()
{	
	sed "$ a get_code(\"$@\");" encode_fun.js > temp_js.js
	js -f temp_js.js
	#rm temp_js.js
}

encode_URL()
{
	encode_pwds=$(encode "$2" | grep -o '[0-9A-Z]*')
	pwd0=$(echo "$encode_pwds" | head -1 )
	pwd1=$(echo "$encode_pwds" | tail -1 )
	URL="http://${IP}/webapps/login/?user_id=$1&password=&x=0&y=0&new_loc=&auth_type=&one_time_token=$2&remote-user=&action=login&encoded_pw=$pwd0&encoded_pw_unicode=$pwd1"

	echo $URL 
}

IP='202.107.226.189' #e-learning.cjlu.edu.cn
echo "login..."
sleep 3
curl -c cookie "http://${IP}/webapps/login/" > /dev/null
echo "get token..."
sleep 1
get_token=$(curl -b cookie "http://${IP}/webapps/login/main.jsp?initialPageLoad=true&userMsg=%E8%AF%A5%E8%AF%B7%E6%B1%82%E6%B2%A1%E6%9C%89%E6%8F%90%E4%BE%9B%E9%AA%8C%E8%AF%81%E8%AF%81%E4%B9%A6%E3%80%82" | grep -o "value=\"[A-Z0-9].*\"" | grep -o "[A-Z0-9]*")
echo "token=${get_token}"
get_URL=$(encode_URL 0800903133 "$get_token" ) 

echo "request: ${get_URL}"
sleep 3

curl -b cookie "$get_URL" > /dev/null

echo "request: frame"
sleep 2
curl -b cookie "http://${IP}/webapps/portal/frameset.jsp" > /dev/null

echo "request: frame tab"
sleep 3
curl -b cookie "http://${IP}/webapps/portal/frameset.jsp?tab_id=_2_1&url=%2fwebapps%2fblackboard%2fexecute%2flauncher%3ftype%3dCourse%26id%3d_1321_1%26url%3d" > /dev/null

echo "start hacking..."
sleep 3

cont=40000
while :
do
cont=$(( $cont + 1 ))
echo "clicking $cont ..."
curl -c cookie -b cookie "http://${IP}/webapps/blackboard/content/listContent.jsp?course_id=_1321_1&content_id=_40592_1&mode=reset" > /dev/null
sleep 0.9
curl -i -c cookie -b cookie "http://${IP}/webapps/blackboard/content/listContent.jsp?course_id=_1321_1&content_id=_76413_1&mode=reset" | grep 'Moved'
sleep 0.9
done
echo "over"
