#!/bin/sh
git add .
git commit -m "`date +F` - auto update"
git push
