<?php

namespace GrantHolle\PowerSchool\Plugin;

use Spatie\ArrayToXml\ArrayToXml;

class PluginXmlBuilder
{
    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $deletable = true;

    /**
     * @var bool
     */
    protected $oauth = false;

    /**
     * @var string
     */
    protected $publisher;

    /**
     * @var string
     */
    protected $publisherEmail;

    /**
     * @var array
     */
    protected $accessRequests = [];

    /**
     * @var OpenId
     */
    protected $openId;

    /**
     * @var array
     */
    protected $links = [];

    /**
     * @var Saml
     */
    protected $saml;

    /**
     * @var string
     */
    protected $registrationUrl;

    /**
     * @var string
     */
    protected $registrationCallback;

    /**
     * @var bool
     */
    protected $autoEnable = false;

    /**
     * @var bool
     */
    protected $autoRegister = false;

    /**
     * @var bool
     */
    protected $autoDeploy = false;

    /**
     * PluginBuilder constructor.
     *
     * @param string|null $version
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct(string $version = null, string $name = null, string $description = null)
    {
        $this->version = $version;
        $this->name = $name;
        $this->description = $description;
    }

    public function publisher(string $name, string $email): PluginXmlBuilder
    {
        $this->publisher = $name;
        $this->publisherEmail = $email;

        return $this;
    }

    public function version(string $version): PluginXmlBuilder
    {
        $this->version = $version;

        return $this;
    }

    public function name(string $name): PluginXmlBuilder
    {
        $this->name = $name;

        return $this;
    }

    public function description(string $description): PluginXmlBuilder
    {
        $this->description = $description;

        return $this;
    }

    public function cantDelete(): PluginXmlBuilder
    {
        $this->deletable = false;

        return $this;
    }

    public function oauth(bool $oauth = true): PluginXmlBuilder
    {
        $this->oauth = $oauth;

        return $this;
    }

    public function addAccessRequest(string $table, string $field, bool $fullAccess = false): PluginXmlBuilder
    {
        $this->accessRequests[] = new AccessRequest($table, $field, $fullAccess);

        return $this;
    }

    /**
     * Adds a link with the given ui contexts to the open id tag
     *
     * @param string $displayText
     * @param string $path
     * @param string $title
     * @param array|string $uiContexts
     * @return $this
     */
    public function addLink(string $displayText, string $path, string $title = '', $uiContexts = []): PluginXmlBuilder
    {
        $this->links[] = new Link($displayText, $path, $title, $uiContexts);

        return $this;
    }

    public function openId(string $host, int $port = 443): PluginXmlBuilder
    {
        $this->openId = new OpenId($host, $port);

        return $this;
    }

    /**
     * Adds a link with the given ui contexts to the open id tag
     *
     * @param string $displayText
     * @param string $path
     * @param string $title
     * @param array|string $uiContexts
     * @return $this
     * @throws \Exception
     */
    public function addOpenIdLink(string $displayText, string $path, string $title = '', $uiContexts = []): PluginXmlBuilder
    {
        if (!$this->openId) {
            throw new \Exception("Open ID tag not properly configured, call `openId()` before adding links");
        }

        $this->openId->addLink($displayText, $path, $title, $uiContexts);

        return $this;
    }

    /**
     * Configures a
     *
     * @param string $name
     * @param string $entityId
     * @param string $baseUrl
     * @param string $metadataUrl
     * @return $this
     */
    public function saml(string $name, string $entityId, string $baseUrl, string $metadataUrl = ''): PluginXmlBuilder
    {
        $this->saml = new Saml($name, $entityId, $baseUrl, $metadataUrl);

        return $this;
    }

    /**
     * Adds a link with the given ui contexts to the SAML tag
     *
     * @param string $displayText
     * @param string $path
     * @param string $title
     * @param array|string $uiContexts
     * @return $this
     * @throws \Exception
     */
    public function addSamlLink(string $displayText, string $path, string $title = '', $uiContexts = []): PluginXmlBuilder
    {
        if (!$this->saml) {
            throw new \Exception("SAML tag not properly configured, call `saml()` before adding links");
        }

        $this->saml->addLink($displayText, $path, $title, $uiContexts);

        return $this;
    }

    /**
     * Adds an attribute for SAML SSO
     *
     * @param string $type
     * @param string $name
     * @param string $attributeName
     * @param string $attributeValue
     * @return $this
     * @throws Exceptions\InvalidUserTypeException
     * @throws \Exception
     */
    public function addSamlAttribute(string $type, string $name, string $attributeName = '', string $attributeValue = ''): PluginXmlBuilder
    {
        if (!$this->saml) {
            throw new \Exception("SAML tag not properly configured, call `saml()` before adding attributes");
        }

        $this->saml->addAttribute($type, $name, $attributeName, $attributeValue);

        return $this;
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $value
     * @return $this
     * @throws \Exception
     */
    public function addSamlPermission(string $name, string $description, string $value): PluginXmlBuilder
    {
        if (!$this->saml) {
            throw new \Exception("SAML tag not properly configured, call `saml()` before adding permissions");
        }

        $this->saml->addPermission($name, $description, $value);

        return $this;
    }

    public function registration(string $url, string $callback): PluginXmlBuilder
    {
        $this->registrationUrl = $url;
        $this->registrationCallback = $callback;

        return $this;
    }

    public function autoEnable(): PluginXmlBuilder
    {
        $this->autoEnable = true;

        return $this;
    }

    public function autoRegister(): PluginXmlBuilder
    {
        $this->autoRegister = true;

        return $this;
    }

    public function autoDeploy(): PluginXmlBuilder
    {
        $this->autoDeploy = true;

        return $this;
    }

    /**
     * Builds the plugin as an array to be ingested by ArrayToXml
     *
     * @return array[]
     */
    public function toArray()
    {
        $plugin = [
            'publisher' => [
                '_attributes' => [
                    'name' => $this->publisher,
                ],
                'contact' => [
                    '_attributes' => [
                        'email' => $this->publisherEmail,
                    ],
                ],
            ],
        ];

        if ($this->oauth) {
            $plugin['oauth'] = [];
        }

        if ($this->openId) {
            $plugin['openid'] = $this->openId->toArray();
        }

        if (!empty($this->links)) {
            $plugin['links'] = [
                'link' => array_map(function (Link $link) {
                    return $link->toArray();
                }, $this->links),
            ];
        }

        if ($this->registrationUrl) {
            $plugin['registration'] = [
                '_attributes' => [
                    'url' => $this->registrationUrl,
                ],
            ];

            if ($this->registrationCallback) {
                $plugin['registration']['callback-data'] = [
                    '_value' => $this->registrationCallback,
                ];
            }
        }

        if (!empty($this->accessRequests)) {
            $plugin['access_request'] = [
                'field' => array_map(function (AccessRequest $request) {
                    return $request->toArray();
                }, $this->accessRequests),
            ];
        }

        if ($this->saml) {
            $plugin['saml'] = $this->saml->toArray();
        }

        if ($this->autoEnable || $this->autoRegister || $this->autoDeploy) {
            $plugin['autoinstall'] = [
                '_attributes' => [
                    'required' => 'true',
                ],
            ];

            if ($this->autoEnable) {
                $plugin['autoinstall']['autoenable'] = [
                    '_attributes' => [
                        'required' => 'true',
                    ],
                ];
            }

            if ($this->autoRegister) {
                $plugin['autoinstall']['autoregister'] = [
                    '_attributes' => [
                        'required' => 'true',
                    ],
                ];
            }

            if ($this->autoDeploy) {
                $plugin['autoinstall']['autoredeploy'] = [];
            }
        }

        return $plugin;
    }

    public function create(bool $pretty = true, bool $validate = true)
    {
        $arrayToXml = new ArrayToXml(
            $this->toArray(),
            [
                'rootElementName' => 'plugin',
                '_attributes' => [
                    'version' => $this->version,
                    'name' => $this->name,
                    'description' => $this->description,
                    'deletable' => $this->deletable ? 'true' : 'false',
                    'xmlns' => 'http://plugin.powerschool.pearson.com',
                    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                    'xsi:schemaLocation' => 'http://plugin.powerschool.pearson.com plugin.xsd',
                ],
            ],
            true,
            'UTF-8'
        );

        if ($pretty) {
            $arrayToXml->prettify();
        }

        $output = $arrayToXml->toXml();

        if ($validate) {
            $validator = new Validator($output);

            if ($validator->invalid()) {
                $validator->throwError();
            }
        }

        return $output;
    }
}
