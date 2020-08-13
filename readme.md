# PowerSchool plugin.xml Builder

A package to help build valid xml for a PowerSchool plugin.

## Installation

```bash
composer require grantholle/powerschool-plugin-xml-builder
```

## Usage

You can fluently build up the tags and attributes of your plugin to generate valid xml.

```php
use GrantHolle\PowerSchool\Plugin\PluginXmlBuilder;
use GrantHolle\PowerSchool\Plugin\UiContext;

/**
 * @var string
 */
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
    ->autoEnable()
    ->autoRegister()
    ->autoDeploy()
    ->cantDelete()
    // By default is "pretty" and validated
    ->create();
    // Not pretty
    // ->create(false);
    // Not pretty or validated
    // ->create(false, false);
```

By default, the xml will be validated against the [spec](src/plugin.xsd). If you'd like for it to not be "pretty," pass `false` to `create()`.

The above snippet will generate the following `plugin.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<plugin xmlns="http://plugin.powerschool.pearson.com" version="1.1.1" name="Simple Plugin" description="A simple plugin" deletable="false" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://plugin.powerschool.pearson.com plugin.xsd">
  <publisher name="Joe Montana">
    <contact email="joe@nfl.com"/>
  </publisher>
  <oauth/>
  <openid host="example.com" port="8443">
    <links>
      <link display-text="My OpenID link" path="/path" title="My OpenID title">
        <ui_contexts>
          <ui_context id="admin.header"/>
          <ui_context id="student.header"/>
        </ui_contexts>
      </link>
    </links>
  </openid>
  <links>
    <link display-text="My link" path="http://example.com" title="My title">
      <ui_contexts>
        <ui_context id="guardian.header"/>
      </ui_contexts>
    </link>
  </links>
  <access_request>
    <field table="students" field="dcid" access="ViewOnly"/>
    <field table="students" field="first_name" access="FullAccess"/>
    <field table="students" field="last_name" access="FullAccess"/>
  </access_request>
  <saml name="mysaml" entity-id="http://example.com/saml" base-url="http://example.com/" metadata-url="http://example.com/metadata">
    <links>
      <link display-text="SAML" path="http://example.com/saml/login" title="SAML SP">
        <ui_contexts>
          <ui_context id="admin.header"/>
          <ui_context id="admin.left_nav"/>
        </ui_contexts>
      </link>
    </links>
    <attributes>
      <user type="admin">
        <attribute name="firstName"/>
        <attribute name="lastName" attribute-name="altLastName" attribute-value="myvalue"/>
      </user>
      <user type="student">
        <attribute name="first_name" attribute-name="first"/>
      </user>
    </attributes>
    <permissions>
      <permission name="permission1" description="A sample permission" value="abc123"/>
      <permission name="permission2" description="Another sample permission" value="def456"/>
    </permissions>
  </saml>
  <autoinstall required="true">
    <autoenable required="true"/>
    <autoregister required="true"/>
    <autoredeploy/>
  </autoinstall>
</plugin>
```

## License

[MIT](LICENSE.md)
