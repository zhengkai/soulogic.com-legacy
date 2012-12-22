#!/bin/sh
mysqldump --opt -d --dump-date=FALSE --databases soulogic soulogic_pda | sed 's/\(.*ENGINE.*AUTO_INCREMENT=\).*/\10;/g' > structure.sql
