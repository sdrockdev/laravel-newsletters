<?php

namespace Sdrockdev\Newsletters\Tests;

use PHPUnit\Framework\TestCase;
use Sdrockdev\Newsletters\NewsletterFactory;
use Spatie\Newsletter\Newsletter;
use Spatie\Newsletter\NewsletterListCollection;

class NewsletterFactoryTest extends TestCase
{
    protected $factory;

    public function setUp() : void
    {
        $this->factory = new NewsletterFactory([
            'driver' => 'api',
            'accounts' => [
                'sdrock' => [
                    'apiKey' => 'sdrock-apikey',
                    'lists' => [
                        'list1' => [ 'id' => 123 ],
                        'list2' => [ 'id' => 456 ],
                    ],
                    'defaultListName' => 'list1',
                ],
                'miles' => [
                    'apiKey' => 'miles-apikey',
                    'lists' => [
                        'list3' => [ 'id' => 789 ],
                        'list4' => [ 'id' => 012 ],
                    ],
                    'defaultListName' => 'list3',
                ],
            ],
            'defaultAccountName' => 'rockchurch',
            'ssl' => true,
        ]);
    }

    /** @test */
    public function it_can_make_an_account()
    {
        $newsletter = $this->factory->create('sdrock');
        $this->assertInstanceOf(Newsletter::class, $newsletter);
    }

    /** @test */
    public function it_fails_on_nonexistent_account_key()
    {
        $this->expectException(\Exception::class);
        $newsletter = $this->factory->create('jfajkjadsfsafsdfsa');
    }

    public function tearDown() : void
    {
        $this->factory = null;
    }
}

