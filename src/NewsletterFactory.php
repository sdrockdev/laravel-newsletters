<?php

namespace Sdrockdev\Newsletters;

use DrewM\MailChimp\MailChimp;
use Spatie\Newsletter\Newsletter;
use Spatie\Newsletter\NewsletterListCollection;
use Illuminate\Support\Arr;

class NewsletterFactory
{
    protected $config;
    protected $newsletters = [];
    protected $mailChimps = [];

    function __construct($config) {
        $this->config = $config;
    }

    function account(string $accountName) : Newsletter
    {
        // Return the newsletter if it has already been created
        if ( isset($this->newsletters[$accountName]) ) {
            return $this->newsletters[$accountName];
        }

        // Make sure this account is defined in the config
        if ( ! Arr::has($this->config, 'accounts.' . $accountName) ) {
            throw new Exceptions\AccountDoesNotExistException;
        }

        // Make sure this account is defined in the config
        $apiKey = Arr::get( $this->config, 'accounts.' . $accountName . '.apiKey');

        // Get or initialize the mailchimp for this key
        $mailChimp = $this->_getMailchimpForAccount($apiKey);

        $mailChimp->verify_ssl = Arr::get( $this->config, 'ssl', true );

        // Get this lists as defined in the config
        $accountLists = Arr::get( $this->config, 'accounts.' . $accountName);

        $configuredLists = NewsletterListCollection::createFromConfig($accountLists);

        // Instantiate the newsletter with the mailchimp instance and defined lists
        $result = new Newsletter($mailChimp, $configuredLists);

        // Cache it in the factory
        $this->newsletters[$accountName] = $result;

        return $result;
    }

    protected function _getMailchimpForAccount($apiKey) : MailChimp {
        if ( isset($this->mailChimps[$apiKey]) ) {
            return $this->mailChimps[$apiKey];
        }
        $result = new MailChimp($apiKey);
        $this->mailChimps[$apiKey] = $result;
        return $result;
    }
}
