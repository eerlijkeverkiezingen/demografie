#!/bin/bash
git config --global user.name EerlijkeVerkiezingen
git config --global user.mail contact@eerlijkeverkiezingen.nl

git config --global -l

git status 

COMMIT=$1

if [ -z "$COMMIT" ]; then
	if [ -f note.txt ]; then
	COMMIT="`cat note.txt`"
	rm note.txt
	fi
fi

if [ -z "$COMMIT" ]; then
	COMMIT="auto update"
fi

# //echo $COMMIT
# //exit

if [ "`git status`" != "`cat ../unchanged-git-status`"  ]; then
	git add .
	git commit -m "[`date +%F`] $COMMIT"
	git push
fi
