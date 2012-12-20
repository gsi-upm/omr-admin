#!/bin/bash
JAR_NAME=ranking.jar
DIRECTORY_OF_JAR=./omr-admin/ranking

echo ----------Omelette ranking----------
cd $DIRECTORY
java -jar $JAR_NAME

