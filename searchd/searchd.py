from http.server import BaseHTTPRequestHandler, HTTPServer
import tkblog_search_engine
import json

port=8901

def searchd_main(json_request):
	#print('received json:', json_request)
	response = {'error':True}
	response = {'desc':'searchd receives invalid action.'}

	if json_request['action'] == 'search':
		response['error'] = False
		response['desc'] = tkblog_search_engine.whoosh_search(
		                         json_request['query'], json_request['page']) 
	elif json_request['action'] == 'index':
		response['error'] = False
		response['desc'] = tkblog_search_engine.whoosh_index_file(
		                         json_request['path'])
	elif json_request['action'] == 'index_all':
		response['error'] = False
		response['desc'] = tkblog_search_engine.whoosh_index_all()
	elif json_request['action'] == 'clear':
		response['error'] = False
		response['desc'] = tkblog_search_engine.whoosh_clear_index()

	response_str = json.dumps(response)
	return response_str

def json_response_send(self, response):
	self.send_response(200)
	self.send_header('Content-type', 'text/html')
	self.send_header('Content-length', len(response))
	self.end_headers()
	self.wfile.write(response.encode('utf-8'))

class myHandler(BaseHTTPRequestHandler):
	def do_POST(self):
		content_len = int(self.headers['Content-Length'])
		raw_req = self.data_string = self.rfile.read(content_len)
		response_str = searchd_main(json.loads(raw_req.decode('utf-8')))
		json_response_send(self, response_str)

try:
	server = HTTPServer(('', port), myHandler)
	print("listening on localhost port " + str(port))
	server.serve_forever()
except KeyboardInterrupt:
	print('close the server...')
	server.socket.close()
