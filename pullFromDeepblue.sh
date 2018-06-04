#!/bin/bash
echo "what file do you want to download?"
read file
scp cst166@deepblue.cs.camosun.bc.ca:/home/student/cst166/public_html/$file $file
mv $file public_html/$file
