<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskDependencyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'dependency_id' => 'required|exists:tasks,id',
        ];
    }

    public function authorize()
    {
        return $this->user()->role === 'manager';
    }
}