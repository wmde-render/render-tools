#!/bin/bash

TIMESTAMP=$(date +%F_%R)

time /opt/ts/python/2.7/bin/python \
 -m cProfile -o profile-$TIMESTAMP \
 ChangeDetector.py \
 > profile-msgs-$TIMESTAMP 2>&1 \
 & disown
