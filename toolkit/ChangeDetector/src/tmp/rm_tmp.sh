#! /bin/sh
#$ -l h_rt=2:05:00 
#$ -l virtual_free=20M
#$ -j y
#$ -N RenderDelCreTmpDump
#$ -wd /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/
# deletes tmp*.dump files older than 4 days
find /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/tmp*.dump -mtime +4 -exec rm {} \;


