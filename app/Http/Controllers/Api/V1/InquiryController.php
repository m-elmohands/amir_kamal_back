<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): JsonResponse
    {
        $inquiry = Inquiry::create($request->validated());

        return response()->json(['data' => ['id' => $inquiry->id], 'message' => 'Your inquiry has been received. Our team will contact you shortly.', 'errors' => null], 201);
    }
}
