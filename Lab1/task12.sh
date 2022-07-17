#!/usr/bin/env bash

if [ $# -lt 1 ]; then
  echo "usage: $(basename "$0") [commit ref]"
  exit 1
fi

set -o pipefail

# display name of files (only names, not paths) that were changed in given commit
if git show --format='' --name-only "$1" | awk -F/ '{ print $NF }'
then
  echo "execution completed successfully"
else
  echo "execution failed"
fi
