#!/bin/sh
mysqldump --opt -d --databases soulogic soulogic_pda | sed 's/\(.*ENGINE.*AUTO_INCREMENT=\).*/\10;/g' | gzip > structure.sql.gz

