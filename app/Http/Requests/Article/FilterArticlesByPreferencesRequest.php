<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class FilterArticlesByPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sources' => ['array', 'required_without_all:categories,authors'],
            'sources.*' => ['string'],

            'categories' => ['array', 'required_without_all:sources,authors'],
            'categories.*' => ['string'],

            'authors' => ['array', 'required_without_all:sources,categories'],
            'authors.*' => ['string'],
        ];
    }
}
