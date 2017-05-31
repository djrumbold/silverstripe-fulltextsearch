<?php

namespace SilverStripe\FullTextSearch\Search;

use SilverStripe\Control\RequestFilter;
use SilverStripe\Control\Session;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\ORM\DataModel;

class SearchUpdater_BindManipulationCaptureFilter implements RequestFilter
{
    public function preRequest(HTTPRequest $request, Session $session, DataModel $model)
    {
        SearchUpdater::bind_manipulation_capture();
    }

    public function postRequest(HTTPRequest $request, HTTPResponse $response, DataModel $model)
    {
        /* NOP */
    }
}
