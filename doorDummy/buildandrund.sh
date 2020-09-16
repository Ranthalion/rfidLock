#!/bin/sh

docker build -t flask1 . && docker run -p 5000:5000 flask