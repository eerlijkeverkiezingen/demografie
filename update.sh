#!/bin/sh
git config --global user.name EerlijkeVerkiezingen
git config --global user.mail contact@eerlijkeverkiezingen.nl

git config --global -l

git add .
git commit -m "[`date +%F`] - auto update"
git push
