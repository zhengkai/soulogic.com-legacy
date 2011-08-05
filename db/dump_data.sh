#!/bin/sh
mysqldump -t --databases soulogic soulogic_pda | gzip > data.sql.gz

