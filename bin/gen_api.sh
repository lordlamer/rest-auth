#!/bin/bash
# generate api documentation

# folder for api eport
OUTDIR=doc/api

# folder with libs
LIBDIR=`dirname $0`/../lib

# run phpdoc
phpdoc -d $LIBDIR -t $OUTDIR