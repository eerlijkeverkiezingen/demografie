#!/bin/bash
git config --global user.name EerlijkeVerkiezingen
git config --global user.mail contact@eerlijkeverkiezingen.nl

git config --global -l

git status 

if [ "`git status`" != "`cat ../unchanged-git-status`"  ]; then
	git add .
	git commit -m "[`date +%F`] auto update"
	git push
fi
