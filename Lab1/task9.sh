#!/usr/bin/env bash

explain_execution_status () {
  set +x
  echo -n "Execution status: $1, which means: "

  if [ "$1" -eq 0 ]; then
    echo "success"
  else
    echo "failure"
  fi
  set -x
}

set -x  # print commands

ls > /dev/null 2>&1
explain_execution_status $?

stat /invalid-path 2> /dev/null
explain_execution_status $?
