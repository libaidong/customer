#!/bin/bash

# Perform a mysqldump backup operation and transfer the backup file to Dropbox
#
# @author MFDC

DB='sitesync'
DBUSER='web'
DBPASS='aZuyea2tohhee3u'
DUMP_PATH=/home/dropbox/Dropbox/SiteSync_DB_Backups
DUMP_FILENAME="sitesync_`date +%Y-%m-%d_%H:%M:%S`"

# Ensure directory path exists
if [ ! -d $DUMP_PATH ]; then
	mkdir $DUMP_PATH
fi 

mysqldump -u"${DBUSER}" -p"${DBPASS}" ${DB} > ${DUMP_PATH}/${DUMP_FILENAME}.sql

