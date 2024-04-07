<?php

namespace WebId\Breadcrumb;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class BreadcrumbElement
{
    private function __construct(
        private string $url,
        private string $title
    ) {
    }

    /**
     * @param array<string, string> $breadcrumb
     * @throws ValidationException
     */
    public static function make(array $breadcrumb): self
    {
        $validated = Validator::make($breadcrumb, [
            'url' => ['required', 'url'],
            'title' => ['present', 'string'],
        ])->validate();

        return new self(
            url: $validated['url'],
            title: $validated['title']
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
        ];
    }
}
