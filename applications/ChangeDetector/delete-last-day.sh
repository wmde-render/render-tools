#!/bin/bash

# delete data from yesterday so we have a more realistic test case for profiling.

YESTERDAY=$(date --date=yesterday +%Y%m%d)

(echo "delete from revision where day >= $YESTERDAY;"
 echo "delete from page where day >= $YESTERDAY;"
 echo "delete from edit_count where day >= $YESTERDAY;"
 echo "delete from noticed_article where day >= $YESTERDAY;") | mysql u_jkroll_change_detector_u
