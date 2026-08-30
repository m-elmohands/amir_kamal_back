<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Inquiries\UpdateInquiryWorkflow;
use App\DataTables\InquiryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateInquiryWorkflowRequest;
use App\Models\Inquiry;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.inquiries', [
            'assignees' => User::query()->where('is_admin', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function data(InquiryDataTable $dataTable): JsonResponse
    {
        return $dataTable->response();
    }

    public function update(UpdateInquiryWorkflowRequest $request, Inquiry $inquiry, UpdateInquiryWorkflow $action, AuditService $audit): RedirectResponse
    {
        $data = $request->validated();
        $action->handle($inquiry, $data);
        $audit->record($request, 'inquiry.workflow_updated', $inquiry, $data);

        return back()->with('success', __('Inquiry workflow updated.'));
    }

    public function destroy(Request $request, Inquiry $inquiry, AuditService $audit): RedirectResponse
    {
        $audit->record($request, 'inquiry.deleted', $inquiry);
        $inquiry->delete();

        return back()->with('success', 'Inquiry moved to trash.');
    }
}
