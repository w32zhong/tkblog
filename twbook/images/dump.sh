#!/bin/bash
echo "input a file name to log"
read file_name
echo "listening..."
tcpdump -s 0 -i 2 -w dump_temp host e-learning.cjlu.edu.cn and tcp 
tcpdump -A -r dump_temp > "$file_name" 
rm dump_temp
echo "over"
