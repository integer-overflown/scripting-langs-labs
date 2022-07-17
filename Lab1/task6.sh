#!/usr/bin/env bash

echo "-- Passing variables to subprocesses --"

# Way 1, export the variable (set it in environment of current process, child processes inherit parent's environment)
export VAR='Hello, World'
bash -c 'echo "1) $VAR"'

# Way 2, pass it as positional argument
# man quote:
#  The assignment to $0 sets the name of the shell, which is used in warning and error messages.
bash -c 'echo "2) $1"' "$SHELL" "$VAR"
