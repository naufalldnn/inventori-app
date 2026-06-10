<?php

namespace App\Http\Controllers;

use App\Models\ReportEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportEntryController extends Controller
{
    public function index(Request $request): View
    {
        $reports = ReportEntry::with('user')
            ->when($request->type, fn ($query, $type) => $query->where('type', $type))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->date_from, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('report-entries.index', compact('reports'));
    }

    public function create(): View
    {
        return view('report-entries.form', ['report' => new ReportEntry()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['user_id'] = $request->user()->id;

        ReportEntry::create($data);

        return redirect()->route('report-entries.index')->with('success', 'Catatan laporan berhasil disimpan.');
    }

    public function show(ReportEntry $reportEntry): View
    {
        $reportEntry->load('user');

        return view('report-entries.show', ['report' => $reportEntry]);
    }

    public function edit(ReportEntry $reportEntry): View
    {
        return view('report-entries.form', ['report' => $reportEntry]);
    }

    public function update(Request $request, ReportEntry $reportEntry): RedirectResponse
    {
        $reportEntry->update($this->validatedData($request));

        return redirect()->route('report-entries.show', $reportEntry)->with('success', 'Catatan laporan berhasil diperbarui.');
    }

    public function destroy(ReportEntry $reportEntry): RedirectResponse
    {
        $reportEntry->delete();

        return redirect()->route('report-entries.index')->with('success', 'Catatan laporan berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(ReportEntry::TYPES)],
            'status' => ['required', Rule::in(ReportEntry::STATUSES)],
            'period_from' => ['nullable', 'date'],
            'period_to' => ['nullable', 'date', 'after_or_equal:period_from'],
            'summary' => ['required', 'string', 'max:5000'],
        ]);
    }
}
