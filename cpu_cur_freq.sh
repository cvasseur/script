#!/bin/bash

count=0;
sum=0;

for cpu in `cat /proc/cpuinfo|grep processor|cut -d' ' -f 2 `
do
        echo -n "Cpu $cpu : "
	freq=`cat /sys/devices/system/cpu/cpu$cpu/cpufreq/cpuinfo_cur_freq`
	echo $freq;
	count=`echo $count+1|bc`;
	sum=`echo $sum+$freq|bc`;
done

echo "count : $count, sum : $sum"
echo -n "avg: "
echo `echo $sum/$count | bc`

