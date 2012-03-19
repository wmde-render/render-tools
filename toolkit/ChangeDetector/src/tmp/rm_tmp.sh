#! /bin/sh
#$ -l h_rt=2:05:00 
#$ -l virtual_free=20M
#$ -j y
#$ -N RenderDelCreTmpDump
#$ -wd /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/
# deletes tmp*.dump files older than 4 days
find /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/tmp*.dump -mtime +4 -exec rm {} \;

#set-up new tmp-files
qsub -N RenderCreTmpEUon /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/construct_tmp_dump_for_Langgroup.php EU on
qsub -N RenderCreTmpEUoff /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/construct_tmp_dump_for_Langgroup.php EU off
qsub -N RenderCreTmpAllon /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/construct_tmp_dump_for_Langgroup.php All on
qsub -N RenderCreTmpAlloff /home/project/r/e/n/render/public_html/toolkit/ChangeDetector/src/tmp/construct_tmp_dump_for_Langgroup.php All off

