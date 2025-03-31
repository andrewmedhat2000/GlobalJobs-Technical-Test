<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'assignee_id' => 'required|exists:users,id',
        ];
    }

    public function authorize()
    {
        return $this->user()->role === 'manager';
    }
}