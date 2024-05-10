<?php

namespace WebId\Breadcrumb;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

readonly class BreadcrumbElement
{
    private function __construct(
        private string $title,
        private ?string $url,
    ) {
    }

    /**
     * @param  array<string, string>  $breadcrumb
     *
     * @throws ValidationException
     */
    public static function make(array $breadcrumb): self
    {
        $validated = Validator::make($breadcrumb, [
            'title' => ['present', 'string'],
            'url' => ['url'],
        ])->validate();

        return new self(
            title: $validated['title'],
            url: $validated['url'] ?? null,
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
        ];
    }
}
