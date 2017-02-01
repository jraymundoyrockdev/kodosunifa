#!/usr/bin/env php
<?php

copy(__DIR__ . '/pre-push', __DIR__ . '/../../../../.git/hooks/pre-push');
chmod(__DIR__ . '/../../../../.git/hooks/pre-push', 0777);