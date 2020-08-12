<?php


namespace Tests;

use GrantHolle\PowerSchool\Plugin\Exceptions\XmlValidationError;
use GrantHolle\PowerSchool\Plugin\PluginXmlBuilder;
use GrantHolle\PowerSchool\Plugin\UiContext;
use PHPUnit\Framework\TestCase;

class PluginXmlTest extends TestCase
{
    public function test_can_generate_simple_xml(): void
    {
        $output = (new PluginXmlBuilder)
            ->version('1.0.0')
            ->description('A simple plugin')
            ->name('Simple Plugin')
            ->publisher('Joe Montana', 'joe@nfl.com')
            ->create();

        $expected = file_get_contents('simple.plugin.xml');

        $this->assertEquals($expected, $output);
    }

    public function test_can_generate_complex_xml(): void
    {
        $output = (new PluginXmlBuilder)
            ->version('1.1.1')
            ->description('A simple plugin')
            ->name('Simple Plugin')
            ->publisher('Joe Montana', 'joe@nfl.com')
            ->openId('example.com', 8443)
            ->addOpenIdLink('My OpenID link', '/path', 'My OpenID title', [
                UiContext::ADMIN_HEADER,
                UiContext::STUDENT_HEADER,
            ])
            ->addLink('My link', 'http://example.com', 'My title', UiContext::GUARDIAN_HEADER)
            ->oauth()
            ->create(true, true);

        $expected = file_get_contents('complex.plugin.xml');

        $this->assertEquals($expected, $output);
    }

    public function test_validation_fails(): void
    {
        $this->expectException(XmlValidationError::class);

        (new PluginXmlBuilder)
            ->description('A simple plugin')
            ->name('')
            ->publisher('Joe', 'joe@nfl.com')
            ->create(true, true);
    }
}