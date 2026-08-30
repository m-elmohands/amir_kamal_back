<?php

namespace App\Actions\Inquiries;

use App\Models\Inquiry;

class UpdateInquiryWorkflow
{
    /** @param array<string, mixed> $data */
    public function handle(Inquiry $inquiry, array $data): Inquiry
    {
        if ($data['status'] === 'quoted' && $inquiry->status !== 'quoted') {
            $data['quoted_at'] = now();
        }

        $data['read_at'] = $inquiry->read_at ?? now();
        $inquiry->update($data);

        return $inquiry->refresh();
    }
}
