<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'due_date' => 'sometimes|date|after:now',
            'status' => 'sometimes|in:pending,completed,canceled',
        ];

        if ($this->user()->role == 'manager') {
            $rules['assignee_id'] = 'sometimes|exists:users,id';
        }

        return $rules;
    }

    public function authorize()
    {
        return true; // Authorization handled in controller
    }
}