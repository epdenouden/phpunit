#!/bin/bash

rm -f data_*.txt

# Prefetch
DP_JIT=0 \
DP_UNLOAD=0 \
./phpunit tests/sandbox/SyntheticDataprovidersTest.php

awk '/TestResult->startTest[[:space:]]/{print $5}' /tmp/phpunit.xt > data_prefetch.txt

# JIT only
DP_JIT=1 \
DP_UNLOAD=0 \
./phpunit tests/sandbox/SyntheticDataprovidersTest.php

awk '/TestResult->startTest[[:space:]]/{print $5}' /tmp/phpunit.xt > data_jit.txt

# JIT with unload
DP_JIT=1 \
DP_UNLOAD=1 \
./phpunit tests/sandbox/SyntheticDataprovidersTest.php

awk '/TestResult->startTest[[:space:]]/{print $5}' /tmp/phpunit.xt > data_unload.txt

# Simulate resource consumption of a Generator driven data provider
DP_JIT=1 \
DP_UNLOAD=1 \
./phpunit tests/sandbox/DummyGeneratorDataprovidersTest.php

awk '/TestResult->startTest[[:space:]]/{print $5}' /tmp/phpunit.xt > data_generator.txt
