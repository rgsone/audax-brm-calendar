#!/bin/bash

# build front
npm run --prefix ./front build

# remove dist if exist
if [ -d dist/ ]
then
  rm -rf dist/
fi

# copy all files to dist
mkdir dist/
cp -r api/ database/ public/ vendor/ view/ dist/
