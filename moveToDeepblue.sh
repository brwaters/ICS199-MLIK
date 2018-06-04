#!/bin/bash
git pull
tar cvf public_html.tar.gz public_html/
scp public_html.tar.gz cst166@deepblue.cs.camosun.bc.ca:public_html.tar.gz
