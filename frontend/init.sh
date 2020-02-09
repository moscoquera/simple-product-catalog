#!/usr/bin/env bash
set -e

FILE=initialized

if [ "$1" = 'apache2-foreground' ]; then
  if [ ! -f "$FILE" ]; then
    ./setup.sh
    touch $FILE
  fi
fi

exec "$@"
