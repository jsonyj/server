#!/bin/bash

step=15 #间隔的秒数，不能大于60

for (( i = 0; i < 60; i=(i+step) )); do
    $(php -f /data/projects/xiaonuo/Admin/Shell/pushWXTemplateMessage.php)
    sleep $step
done

exit 0
