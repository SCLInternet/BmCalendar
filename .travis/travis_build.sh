#!/bin/bash

### Run unit tests
vendor/bin/phpunit
PHPUNIT=$?

### Check coding standards
vendor/bin/phpcs --standard=psr2 src
PHPCS=$?

### Check code quality
#vendor/bin/phpmd src text codesize
#PHPMD=$?

### Check for license headers
LICENSE=0

LICENSE_HEADER="<?php
/**
License here
 */"

#for i in `find src tests -name '*.php'`; do
#    diff <(echo "$LICENSE_HEADER") <(head -7 "$i");
#
#    if [ "$?" -ne "0" ]; then
#        echo "Missing or invalid license header in \"$i\""
#        let LICENSE=1
#    fi
#done

### Display results
EXIT=0

echo
echo "#### RESULTS:"

if [ "$PHPUNIT" -ne "0" ]; then
    echo "**** Unit tests failed"
    EXIT=1
fi
if [ "$PHPCS" -ne "0" ]; then
    echo "**** Coding standards failed"
    EXIT=1
fi
#if [ "$PHPMD" -ne "0" ]; then
#    echo "**** Mess detection failed"
#    EXIT=1
#fi
if [ "$LICENSE" -ne "0" ]; then
    echo "**** License header check failed"
    EXIT=1
fi

exit $EXIT
