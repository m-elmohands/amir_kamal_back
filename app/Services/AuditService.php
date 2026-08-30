<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditService
{
    public function record(Request $request, string $action, ?Model $model = null, array $metadata = []): void
    {
        AuditLog::create(['user_id' => $request->user()?->id, 'action' => $action, 'auditable_type' => $model?->getMorphClass(), 'auditable_id' => $model?->getKey(), 'metadata' => $metadata, 'ip_address' => $request->ip()]);
    }
}
