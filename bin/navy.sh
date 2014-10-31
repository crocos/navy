#!/bin/bash
set -e

usage() {
    echo "USAGE: `basename $0` {start|stop|restart|resume|wait|tail|init}"
    echo "  Navy - github hook auto deploy system command interface."
    echo ""
    echo "Options:"
    echo "  -h, --help          show this help."
    echo "  -v, --verbose       show detail commands."
    echo "  --dry               dry run mode."
    exit 1;
}

NAVY_LOGDIR=/var/log/navy
NAVY_PIDDIR=/var/run/navy
WORKER_LOGFILE=$NAVY_LOGDIR/worker.log
WORKER_PIDFILE=$NAVY_PIDDIR/worker.pid

main() {
    real_script_dir=$(dirname $(readlink -f $0))
    script_dir=$(cd $(dirname $0); pwd)
    opts=`getopt -o hv -l help,verbose,dry, -- "$@"`
    eval set -- "$opts"
    while [ -n "$1" ]; do
        case $1 in
            -h|--help) usage;;
            -v|--verbose) is_verbose=1;;
            --dry) is_dry=1;;
            --) shift; break;;
            *) usage;;
        esac
        shift
    done

    if [ $is_dry ];then
        info "dry run..."
    fi

    case $1 in
        resume)
            do_resume
            ;;
        wait)
            do_wait
            ;;
        start)
            do_start
            ;;
        stop)
            do_stop
            do_resume #delete lockfile
            ;;
        restart)
            do_stop
            do_start
            ;;
        tail)
            do_tail
            ;;
        init)
            do_init
            ;;
        *)
            usage
            ;;
    esac

}

do_resume() {
    run php $real_script_dir/navy.php sail
}

do_wait() {
    run php $real_script_dir/navy.php anchor
}

do_init() {
    if [ ! -e "$NAVY_LOGDIR" ]; then
        mkdir $NAVY_LOGDIR
        info "craete dir: $NAVY_LOGDIR"
    fi
    chmod 0777 $NAVY_LOGDIR

    if [ ! -e "$NAVY_PIDDIR" ]; then
        mkdir $NAVY_PIDDIR
        info "craete dir: $NAVY_PIDDIR"
    fi
    chmod 0777 $NAVY_PIDDIR
}

do_start() {
    if [ -e $WORKER_PIDFILE ]; then
        failure "$WORKER_PIDFILE file already found."
        return 1
    fi

    php $real_script_dir/worker.php &
    if [ $? -eq 0 ]; then
        echo "$!" > $WORKER_PIDFILE
        success "navy worker start succeeded. PID: $!"

        disown %1
    else
        failure "navy worker start failed."
        return 1
    fi
}

do_stop() {
    if [ ! -e $WORKER_PIDFILE ]; then
        failure "$WORKER_PIDFILE file not found."
        return 1
    fi

    local worker_pid=$(cat $WORKER_PIDFILE)
    if [ "x$worker_pid" = "x" ]; then
        failure "pid is empty. remove $WORKER_PIDFILE"
        run rm -f $WORKER_PIDFILE
        return 1
    fi

    run kill -TERM $worker_pid
    if [ "$(ps $worker_pid > /dev/null 2>&1; echo $?)" != "0" ];then
        success "navy worker stop succeeded."
        run rm -f $WORKER_PIDFILE
    else
        failure "navy worker stop failed."
        return 1
    fi
}

do_tail() {
    tail -F $WORKER_LOGFILE
}

## utility
run() {
    if [ $is_dry ]; then
        echo "[dry run] $@"
    else
        if [ $is_verbose ];then
            echo "[run] $@"
        fi
        eval "$@"
    fi
}

red() {
    echo -n "[1;31m$1[0m"
}

yellow() {
    echo -n "[1;33m$1[0m"
}

green() {
    echo -n "[1;32m$1[0m"
}

gray() {
    echo -n "[1;30m$1[0m"
}

fatal() {
    red "[fatal] "
    echo "$1"
}

warn() {
    yellow "[warn] "
    echo "$1"
}

info() {
    green "[info] "
    echo "$1"
}

debug() {
    if [ $is_dry ] || [ $is_verbose ];then
        gray "[debug] "
        echo "$1"
    fi
}

success() {
    echo "[ $(green OK) ] $1"
}

failure() {
    echo "[ $(red NG) ] $1"
}

judge() {
    if [ $1 -eq 0 ];then
        success $2
    else
        failure $2
    fi
}

is_numeric() {
    local value=$1
    expr "$value" : "[0-9]*" > /dev/null
    return $?
}

is_absolute() {
    local path=$(echo $1)
    [ "${path:0:1}" = "/" ]
    return $?
}

resolve_path() {
    local path=$1
    if is_absolute $path; then
        echo $(echo $path)
    else
        echo $(echo `pwd`/$path)
    fi
}

# call main.
main "$@"

