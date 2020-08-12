<?php

namespace GrantHolle\PowerSchool\Plugin;

class OpenId
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var array<Link>
     */
    protected $links = [];

    public function __construct(string $host, int $port = 443)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @param string $displayText
     * @param string $path
     * @param string $title
     * @param array|string $uiContexts
     * @return OpenId
     */
    public function addLink(string $displayText, string $path, string $title = '', $uiContexts = []): OpenId
    {
        $this->links[] = new Link($displayText, $path, $title, $uiContexts);

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            '_attributes' => [
                'host' => $this->host,
                'port' => $this->port,
            ],
        ];

        if (!empty($this->links)) {
            $array['links'] = [
                'link' => array_map(function (Link $link) {
                    return $link->toArray();
                }, $this->links),
            ];
        }

        return $array;
    }
}