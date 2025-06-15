<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ReportArt;
use App\Models\Art;

class ModeratorController extends Controller
{
    // Показать форму жалобы
    public function artReportCreate(Art $art)
    {
        return view('reports.create', compact('art'));
    }

    // Сохранить жалобу
    public function artReportStore(Request $request, Art $art)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        ReportArt::create([
            'user_id' => Auth::id(),
            'art_id' => $art->id,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('art.show', $art->id)
            ->with('success', 'Жалоба успешно отправлена');
    }

    // Список жалоб (для админов)
    
    public function ModerationPanel() {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }

        $activeTab = request()->get('tab', 'requests');

        return view('moderation.panel', [
            'arts' => Art::where('request_status', 'pending')->get(),
            'reports' => ReportArt::with(['user', 'art'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get(),
            'activeTab' => $activeTab
        ]);
    }

    public function artModerate(Art $art) {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        $art->load(['user', 'additionalImages']);
        $editable = auth()->check() && (auth()->id() === $art->user_id || auth()->user()->isModerator());

        return view('arts.moderate', [
            'art' => $art,
            'editable' => $editable,
            'additionalImages' => $art->additionalImages
        ]);
    }

    public function artApprove(Art $art) {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        $art->update(['request_status' => 'approved']);
        return redirect()->route('moderation')->with('success', 'Art approved');
    }

    public function artReject(Art $art) {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }
        $art->update(['request_status' => 'rejected']);
        return redirect()->route('moderation')->with('success', 'Art rejected');
    }

    public function resolveByDeletingArt(ReportArt $report)
    {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }

        // Удаляем арт и отклоняем жалобу
        $art = $report->art;
        $art->delete();
        $art->reports()->update(['status' => 'approved']);

        return back()->with('success', 'Арт удален и жалоба отклонена');
    }

    public function resolveByRejectingReport(ReportArt $report)
    {
        if (!auth()->user()->isModerator()) {
            return redirect()->route('map');
        }

        // Отклоняем жалобу без удаления арта
        $report->update(['status' => 'rejected']);

        return back()->with('success', 'Жалоба отклонена');
    }
}
