<?php

namespace GrantHolle\PowerSchool\Plugin;

class Link
{
    /**
     * @var string
     */
    public $displayText;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $title;

    /**
     * @var array
     */
    public $uiContexts = [];

    /**
     * Link constructor.
     *
     * @param string $displayText
     * @param string $path
     * @param string $title
     * @param array|string $uiContexts
     */
    public function __construct(string $displayText, string $path, string $title = '', $uiContexts = [])
    {
        $this->displayText = $displayText;
        $this->path = $path;
        $this->title = $title;

        if (is_string($uiContexts)) {
            $uiContexts = [$uiContexts];
        }

        $this->withUiContexts($uiContexts);
    }

    public function withUiContext(string $id): Link
    {
        $this->uiContexts[] = new UiContext($id);

        return $this;
    }

    public function withUiContexts(array $ids): Link
    {
        foreach ($ids as $id) {
            $this->withUiContext($id);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            '_attributes' => [
                'display-text' => $this->displayText,
                'path' => $this->path,
                'title' => $this->title,
            ],
            'ui_contexts' => [
                'ui_context' => array_map(function (UiContext $context) {
                    return $context->toArray();
                }, $this->uiContexts),
            ]
        ];
    }
}
