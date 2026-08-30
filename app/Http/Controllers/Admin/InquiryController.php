<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\InquiryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Services\AuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.inquiries');
    }

    public function data(InquiryDataTable $dataTable): JsonResponse
    {
        return $dataTable->response();
    }

    public function update(Request $request, Inquiry $inquiry, AuditService $audit): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:new,in_progress,resolved']]);
        $inquiry->update($data + ['read_at' => $inquiry->read_at ?? now()]);
        $audit->record($request, 'inquiry.status_updated', $inquiry, $data);

        return back()->with('success', 'Inquiry status updated.');
    }

    public function destroy(Request $request, Inquiry $inquiry, AuditService $audit): RedirectResponse
    {
        $audit->record($request, 'inquiry.deleted', $inquiry);
        $inquiry->delete();

        return back()->with('success', 'Inquiry moved to trash.');
    }
}
