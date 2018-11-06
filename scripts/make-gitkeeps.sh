#!/bin/sh -x

# Simply put .gitkeep files into all empty folders.
find . -type d -empty -not -path "./.git/*" -exec touch \{\}/.gitkeep \;
