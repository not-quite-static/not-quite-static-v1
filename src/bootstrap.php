<?php

use nqs\app;
use nqs\pluginManager;

pluginManager::load();

pluginManager::hook("preinit");

app::init();

pluginManager::hook("postinit");

app::run();