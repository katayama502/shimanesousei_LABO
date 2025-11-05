<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\Project|null $project */
        $project = $this->route('project');

        if ($project) {
            return Gate::allows('update', $project);
        }

        return Gate::allows('create', Project::class);
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'organization_id' => [$project ? 'sometimes' : 'required', 'exists:organizations,id'],
            'title' => ['required', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:20000'],
            'sport_category_id' => ['nullable', 'exists:categories,id'],
            'culture_category_id' => ['nullable', 'exists:categories,id'],
            'target_amount' => ['required', 'integer', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'prefecture' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
