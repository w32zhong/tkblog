#!/bin/sh
url_head='http://127.0.0.1/tkblog/resource/search/search_req.php?'
# curl "${url_head}action=search&query=abc&page=1"
# curl "${url_head}action=bad"
# curl "${url_head}action=search&query=abc"
# curl "${url_head}action=search"
# curl "${url_head}action=index"
# curl "${url_head}action=index&path=../blog/2015/8/blog/2015-08-25-01-25-1074.txt"
# curl "${url_head}action=clear"
curl "${url_head}action=index_all"
