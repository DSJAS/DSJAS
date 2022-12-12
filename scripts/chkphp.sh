#!/bin/sh

while [ ! -d ./.git/ ]
do
	cd ..
done

grep -rl "<?php" public/ && exit 1
