<?php
declare(strict_types=1);
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity'   => 'required|integer',
            'unit_price' => 'required|numeric|nullable',
            'type'       => 'required|string',
        ];
    }
}
