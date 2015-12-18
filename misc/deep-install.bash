#!/bin/bash -x

# Install NodeJS deps deeply.
# Useful, when NPM feels like being a douche.
#
# FIXME: Extend with other managers like Bower and Composer.
# @author: Ingwie Phoenix

NODEJS_SCRIPT='Object.keys(require("./package.json").dependencies).join("\n")'
node -p $NODEJS_SCRIPT | while read pkg
do
    npm install $pkg
done
