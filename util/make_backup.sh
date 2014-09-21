#!/bin/bash
mysqldump -u root -p --complete-insert $*
