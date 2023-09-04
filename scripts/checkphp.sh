#!/bin/sh

[ ! -d public ] && cd ..
find public/ -name "*.php" -exec "/usr/bin/php" "-l" "{}" ";"