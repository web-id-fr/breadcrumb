<?php

namespace WebId\Breadcrumb;

class BreadcrumbElement
{
    public function __construct(
        readonly private string $url,
        readonly private string $title
    ) {
    }

    public static function make(array $breadcrumb): self
    {
        return new self(
            url: $breadcrumb['url'],
            title: $breadcrumb['title']
        );
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
        ];
    }
}
