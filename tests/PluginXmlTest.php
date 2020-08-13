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
            ->create(true, false);

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
            ->addAccessRequest('students', 'dcid')
            ->addAccessRequest('students', 'first_name', true)
            ->addAccessRequest('students', 'last_name', true)
            ->saml('mysaml', 'http://example.com/saml', 'http://example.com/', 'http://example.com/metadata')
            ->addSamlLink('SAML', 'http://example.com/saml/login', 'SAML SP', [
                UiContext::ADMIN_HEADER,
                UiContext::ADMIN_LEFT_NAV,
            ])
            ->addSamlAttribute('admin', 'firstName')
            ->addSamlAttribute('admin', 'lastName', 'altLastName', 'myvalue')
            ->addSamlAttribute('student', 'first_name', 'first')
            ->addSamlPermission('permission1', 'A sample permission', 'abc123')
            ->addSamlPermission('permission2', 'Another sample permission', 'def456')
            ->oauth()
            ->create();

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