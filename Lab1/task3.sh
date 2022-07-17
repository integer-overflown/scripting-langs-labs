#!/usr/bin/env bash

debugFlags=()

while [ -n "$1" ]; do
  case "$1" in
  dry-run)
    debugFlags+=('-n')
    shift
    ;;
  print)
    oldIFS=$IFS
    modifiers=$2

    if [ -n "$2" ]; then
      shift
    fi

    IFS=','

    for arg in $modifiers; do
      case "$arg" in
      lines) debugFlags+=('-v') ;;
      commands) debugFlags+=('-x') ;;
      *) echo "Unknown modifier: $arg" ;;
      esac
    done

    IFS=$oldIFS
    shift
    ;;
  *)
    echo "Unknown arg: $1"
    shift
    ;;
  esac
done

echo "Flags: ${debugFlags[*]}"

if [ ${#debugFlags[@]} -gt 0 ]; then
  set ${debugFlags[*]}
fi

# Now do something to demonstrate that 'set' command took effect

echo "Hello, $USER"
echo "And this script has PID $$"
echo "You might be interested in seeing this:"
ps aux | grep $$
