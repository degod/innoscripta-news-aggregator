<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ListArticlesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes','string','max:255'],
            'category' => ['sometimes','string','max:255'],
            'source' => ['sometimes','string','max:255'],
            'author' => ['sometimes','string','max:255'],
            'date' => ['sometimes','date_format:Y-m-d'],
            'per_page' => ['sometimes','integer','min:1','max:100'],
        ];
    }

    public function filters(): array
    {
        return [
            'q' => $this->query('q'),
            'category' => $this->query('category'),
            'source' => $this->query('source'),
            'author' => $this->query('author'),
            'date' => $this->query('date'),
        ];
    }

    public function perPage(): int
    {
        return (int) ($this->query('per_page') ?? 10);
    }
}
