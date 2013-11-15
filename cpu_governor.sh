#!/bin/bash

for cpu in `cat /proc/cpuinfo|grep processor|cut -d' ' -f 2 `
do
	echo "Switch cpu $cpu to governor $1"
	cpufreq-selector -c $cpu -g $1 &
done
