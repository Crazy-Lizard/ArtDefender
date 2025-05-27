@extends('layouts.main-layout')

@section('title', 'ArtDefender : Moderator Panel')

@section('content')
    <div class="moder-header">
        <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        <h1>MODERATION PANEL</h1>

        <a class="header-btn right-btn" style="cursor:default"></a>
    </div>

    <div class="moderation-tabs">
        <a href="?tab=requests" class="{{ $activeTab === 'requests' ? 'active' : '' }}">Art Submissions</a>
        <a href="?tab=reports" class="{{ $activeTab === 'reports' ? 'active' : '' }}">Art Reports</a>
    </div>

    <style>
        .card {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 124px;
            height: 124px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }
        .image {
            /* max-width: 124px; */
            max-height: 128px;
            object-fit: cover;
        }
    </style>

    @if($activeTab === 'requests')
        <div class="submissions-list">
            <div>
                @if ($arts->isEmpty())
                    <div class="alert alert-info">No arts awaiting moderation.</div>
                @else
                    <div class="row">
                        @foreach ($arts as $art)
                            <div class="card">
                                @if ($art->image_url)
                                    <a href="{{ route('arts.moderate', $art->id) }}">
                                        <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                                    </a>
                                @endif
                            </div>
                            <div class="moder-btns-mini">
                                <form method="POST" action="{{ route('arts.approve', $art->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="moder-btn-mini approve-btn">
                                        <img src="{{ asset('icons/white/done-white.png') }}">
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('arts.reject', $art->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="moder-btn-mini reject-btn">
                                        <img src="{{ asset('icons/white/cross-white.png') }}">
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="container">
            <h2>Список жалоб</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Пользователь</th>
                            <th>Арт</th>
                            <th>Причина</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $report->user->name }}</td>
                                <td>
                                    <div class="card">
                                        <a href="{{ route('art.show', $report->art) }}">
                                            <img src="{{ $report->art->image_url }}" class="image card-img-top" alt="Art image">
                                            {{-- {{ $report->art->id }} --}}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ str()->limit($report->reason, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ][$report->status] }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td>
                                    {{-- <form action="{{ route('reports.update', $report) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select" onchange="this.form.submit()">
                                            <option value="approved" {{ $report->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </form> --}}

                                    <div class="moder-btns-mini">
                                        <form method="POST" action="{{ route('reports.resolve.delete-art', $report) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="moder-btn-mini approve-btn" onclick="return confirm('Принять жалобу и удалить арт?')">
                                                <img src="{{ asset('icons/white/done-white.png') }}">
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('reports.resolve.reject', $report) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="moder-btn-mini reject-btn" onclick="return confirm('Отклонить жалобу?')">
                                                <img src="{{ asset('icons/white/cross-white.png') }}">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $reports->links() }} --}}
            </div>
        </div>
    @endif
@endsection