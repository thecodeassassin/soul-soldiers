#!/bin/bash

if [ ! -r params.sh ] ; then
    echo 'Please create a params.sh file from params.sh.dist'
    exit
fi

. params.sh

REMOTE_DB_ARGS="-h$REMOTE_DB_IP -u $REMOTE_DB_USER -p$REMOTE_DB_PASS $REMOTE_DB"
LOCAL_DB_ARGS="-h$LOCAL_DB_IP -u $LOCAL_DB_USER -p$LOCAL_DB_PASS $LOCAL_DB"

IGNORE_PARAMS=""
IGNORE_STR=""

#IGNORE_LIST=(tblUser)

if [ -z $IGNORE_LIST ] ; then
    echo 'Ignore list not set, please check params.sh'
    exit
fi

echo "=> Attempting connection to $REMOTE_DB_IP..."

$(mysql $REMOTE_DB_ARGS -e ';' 2> /dev/null)

if [ $? -ne 0 ] ; then
  echo '=> Connection failed, please check the settings in this file'
  exit
fi


echo "=> Attempting connection to $LOCAL_DB_IP..."

$(mysql $LOCAL_DB_ARGS -e ';' 2> /dev/null)

if [ $? -ne 0 ] ; then
  echo '=> Connection failed, please check the settings in this file'
  exit
fi


echo "=> Connected to $REMOTE_DB_IP"
sleep 1
echo
echo "=> Attempting to download tables"

if [ ! -z $IGNORE_LIST ] ; then
 for i in ${IGNORE_LIST[@]}; do
    echo "=> Ignoring table ${i}"
    IGNORE_PARAMS="${IGNORE_PARAMS} --ignore-table=$REMOTE_DB.${i} "
    IGNORE_STR="${IGNORE_STR},'${i}'"

 done
fi
IGNORE_STR=${IGNORE_STR:1}

#echo ${IGNORE_PARAMS}
echo

ROWS=$(mysql $REMOTE_DB_ARGS -e "SELECT COUNT(*) as 'COUNT' FROM information_schema.tables WHERE table_schema = '${REMOTE_DB}' AND TABLE_NAME NOT IN (${IGNORE_STR});")
IFS=$'\n' ROWS=($ROWS)
echo "=> ${ROWS[1]} tables found, ready for exporting."

#echo "mysqldump -h$REMOTE_DB_IP -u $REMOTE_DB_USER -p$REMOTE_DB_PASS $REMOTE_DB$IGNORE_PARAMS > /tmp/${REMOTE_DB}.sql"

/bin/sh -c "mysqldump $REMOTE_DB_ARGS$IGNORE_PARAMS > /tmp/${REMOTE_DB}.sql"

if [ $? -ne 0 ]; then
   echo "=> Export failed!"
   exit
fi

echo "=> Export successful, attempting import..."

/bin/sh -c "mysql $LOCAL_DB_ARGS < /tmp/${REMOTE_DB}.sql"

if [ $? -ne 0 ]; then
   echo "=> Import failed!"
   exit
fi

echo
echo "=> Import completed, databases in sync"
