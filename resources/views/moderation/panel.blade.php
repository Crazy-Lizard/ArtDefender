@extends('layouts.main-layout')

@section('title', 'ArtDefender : Moderator Panel')

@section('content')
    <div class="moder-header">
        <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
            <img src="{{ asset('icons/white/back-white.png') }}">
        </a>

        {{-- <h1>MODERATION PANEL</h1> --}}
        <h1>МОДЕРАТОРНАЯ</h1>

        <a class="header-btn right-btn" style="cursor:default"></a>
    </div>

    <div class="content" style="min-height: 0vh">

        <div class="moderation-tabs" style="display: flex; gap: 50px; margin-bottom: 20px">
            <a href="?tab=requests" class="{{ $activeTab === 'requests' ? 'active' : '' }}" style="color: whitesmoke">Арт заявки</a>
            <a href="?tab=reports" class="{{ $activeTab === 'reports' ? 'active' : '' }}" style="color: whitesmoke">Арт жалобы</a>
        </div>

        <style>
            .arts-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* 3 колонки */
                gap: 2px; /* Расстояние между элементами */
                width: 100%;
            }

            .card {
                width: 124px;
                height: 124px;
                border-radius: 20px;
                overflow: hidden;
                cursor: pointer;
                position: relative;
            }

            .image-wrapper {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                min-width: 124px;
                min-height: 124px;
            }

            /* Для вертикальных изображений (высота > ширины) */
            .image-portrait {
                width: 125px;
                height: auto;
                min-height: 125px;
            }

            /* Для горизонтальных изображений (ширина > высоты) */
            .image-landscape {
                height: 125px;
                width: auto;
                min-width: 125px;
            }
        </style>

        @if($activeTab === 'requests')
            <div class="submissions-list">
                <div>
                    @if ($arts->isEmpty())
                        <div class="alert alert-info">Нет артов, ожидающих модерации.</div>
                    @else
                        <div class="arts-grid">
                            @foreach ($arts as $art)
                                {{-- <div class="card">
                                    @if ($art->image_url)
                                        <a href="{{ route('arts.moderate', $art->id) }}">
                                            <img src="{{ $art->image_url }}" class="image card-img-top" alt="Art image">
                                        </a>
                                    @endif
                                </div> --}}
                                <div class="mod-card">
                                    <div class="card">
                                        @if ($art->image_url)
                                            <a href="{{ route('arts.moderate', $art->id) }}">
                                                <div class="image-wrapper">
                                                    @php
                                                        // Определяем ориентацию изображения
                                                        list($width, $height) = getimagesize(public_path(parse_url($art->image_url, PHP_URL_PATH)));
                                                        $orientation = ($width > $height) ? 'landscape' : 'portrait';
                                                    @endphp
                                                    <img 
                                                        src="{{ $art->image_url }}" 
                                                        class="image-{{ $orientation }}" 
                                                        alt="Art image"
                                                        onerror="this.onerror=null; this.classList.add('image-error')"
                                                    >
                                                </div>
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
    </div>
@endsection