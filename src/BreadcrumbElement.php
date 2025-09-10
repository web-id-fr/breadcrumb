<?php

namespace WebId\Breadcrumb;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use WebId\Breadcrumb\Exceptions\InvalidBreadcrumbElementException;

readonly class BreadcrumbElement
{
    private function __construct(
        private string $title,
        private ?string $url,
    ) {}

    /**
     * @param  array<string, string>  $breadcrumb
     *
     * @throws InvalidBreadcrumbElementException
     * @throws ValidationException
     */
    public static function make(array $breadcrumb): self
    {
        $validator = Validator::make($breadcrumb, [
            'title' => ['present', 'string'],
            'url' => ['url'],
        ]);

        if ($validator->fails()) {
            throw new InvalidBreadcrumbElementException($validator);
        }

        return new self(
            title: $breadcrumb['title'],
            url: $breadcrumb['url'] ?? null,
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
