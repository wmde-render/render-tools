#! /bin/sh
# deletes in .  tmp*.dump files older than 4 days
find tmp*.dump -mtime +4 -exec rm {} \;
