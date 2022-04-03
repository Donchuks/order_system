<?php

namespace App\Http\Requests;

use App\Enum\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|regex:/^[a-zA-ZÑñ\s]+$/',
            'phone' => 'required|numeric|unique:orders,phone',
            'address' => 'required|string',
            'delivery_date' => 'required|date',
            'payment_option' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'order_status' => [
                'required',
                Rule::in([
                    OrderStatus::ORDER_RECEIVED,
                    OrderStatus::ORDER_PROCESSING,
                    OrderStatus::ORDER_READY_TO_SHIP,
                    OrderStatus::ORDER_SHIPPED,
                    OrderStatus::ORDER_CANCELLED
                ]),
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'product_id.exists' => 'The Selected Product is Invalid',
        ];
    }
}
