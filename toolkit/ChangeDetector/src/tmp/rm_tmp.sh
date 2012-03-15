#! /bin/sh
#$ -l h_rt=0:05:00 
#$ -l virtual_free=5M
#$ -j y
#$ -N RenderDeleteTmpDump
#$ -wd /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/
# deletes tmp*.dump files older than 4 days
find /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/tmp*.dump -mtime +4 -exec rm {} \;
