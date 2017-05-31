<?php

global $databaseConfig;
if (isset($databaseConfig['type'])) SilverStripe\FullTextSearch\Search\SearchUpdater::bind_manipulation_capture();
