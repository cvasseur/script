#!/bin/bash

#
# How to use parallel_commands script
# 
#declare -A commands
#commands=(
#    ["script_1"]="/path/to/script1.sh" 
#    ["script_2"]="/path/to/script2.sh"
#    ["script_3"]="/path/to/script2.sh"
#    ["script_4"]="/path/to/script4.sh"
#    ["cmd_1"]="cmd1"
#);
#
#executeCommands "$commands" 3
#

function executeCommands () {
    function countRunning () {
        activePid=$1;
        count=0;

        for commandName in ${!activePid[@]} ; do
            PID=${activePid[${commandName}]}
            RUNNING=`ps a |grep -E "$PID" | grep -v "grep" | wc -l`
            [ $RUNNING -eq 1 ] && count=$((count + 1));
        done

        echo $count;
    }

    function blockWhileRunningGreaterThan () {
        activePid=$1;
        limit=$2;
    
        running=`countRunning $activePid`;
        while [ $running -gt $limit ]; do
            running=`countRunning $activePid`;
        done     
    }

    commands=$1;
    max_concurrency=$2

    declare -A activePid

    echo "Total command to execute : "${#commands[@]};
    echo "Now executing with max concurrency : $max_concurrency";
    echo "-----------------------------";

    for commandName in ${!commands[@]} ; do
        echo "Launch command $commandName";

        ${commands[${commandName}]} &
        activePid[$commandName]=$!
        unset "commands[$commandName]";
        
        echo "Commands to execute : "${#commands[@]};
        
        [ ${#commands[@]} -gt 0 ] && blockWhileRunningGreaterThan "$activePid" "$max_concurrency"
    done
    
    blockWhileRunningGreaterThan "$activePid" 0

    echo "All commands executed"
}



