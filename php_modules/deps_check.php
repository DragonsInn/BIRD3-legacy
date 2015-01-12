<?php

/**
 * @file
 * @brief Check for dependencies on which BIRD3 relies.
 */

if(!extension_loaded("redis")) {
    echo "The phpredis extension is not loaded.\n";
    echo "Please install it first!\n";
    exit(1);
}
