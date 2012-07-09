#!/bin/bash

TIMESTAMP=$(date +%F_%R)

(time /opt/ts/python/2.7/bin/python \
 -m cProfile -o profile-$TIMESTAMP \
 ChangeDetector.py) \
 | tee profile-msgs-$TIMESTAMP 2>&1