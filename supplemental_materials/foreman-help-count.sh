#!/bin/bash

foreman-installer --full-help > out
grep "\-\-" out | sort > out2
egrep -v "^\-\-" out2 | wc -l
fgrep "[no-]" out2 | wc -l