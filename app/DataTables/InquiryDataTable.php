<?php

namespace App\DataTables;

use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class InquiryDataTable
{
    public function __construct(private readonly DataTables $dataTables) {}

    public function response(): JsonResponse
    {
        $query = Inquiry::query()->with(['product.translations', 'assignee'])->select('inquiries.*');

        return $this->dataTables->eloquent($query)
            ->filterColumn('name', fn ($query, string $keyword) => $query->where(fn ($nested) => $nested->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%")->orWhere('phone', 'like', "%{$keyword}%")))
            ->editColumn('name', fn (Inquiry $inquiry) => '<strong>'.e($inquiry->name).'</strong><br>'.e($inquiry->email).'<br><span class="muted">'.e($inquiry->phone).'</span>')
            ->editColumn('subject', fn (Inquiry $inquiry) => e($inquiry->subject).'<br><span class="muted">'.e($inquiry->product?->name ?? __('General inquiry')).'</span>')
            ->editColumn('message', fn (Inquiry $inquiry) => e(str($inquiry->message)->limit(140)))
            ->editColumn('status', fn (Inquiry $inquiry) => view('admin.inquiries.status', compact('inquiry'))->render())
            ->addColumn('owner', fn (Inquiry $inquiry) => e($inquiry->assignee?->name ?? __('Unassigned')))
            ->addColumn('follow_up', fn (Inquiry $inquiry) => $inquiry->next_follow_up_at?->format('Y-m-d H:i') ?? '—')
            ->addColumn('actions', fn (Inquiry $inquiry) => view('admin.inquiries.actions', compact('inquiry'))->render())
            ->rawColumns(['name', 'subject', 'status', 'actions'])
            ->toJson();
    }
}
