#!/bin/bash

./bin/interpreter examples/test.lang World

cat examples/test.lang | ./bin/interpreter World
