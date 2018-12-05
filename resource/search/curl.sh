#!/bin/bash
curl -H "Content-Type: application/json" -X POST -d '{"action":"search","query":"其实","page":1}' http://localhost:8901
#curl -H "Content-Type: application/json" -X POST -d '{"action":"search","query":"start","page":1}' http://localhost:8901
