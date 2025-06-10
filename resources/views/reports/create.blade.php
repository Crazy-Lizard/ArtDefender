@extends('layouts.main-layout')

@section('content')

    <div class="header">
            <a href="/profile/{{ auth()->user()->id }}" class="header-btn left-btn">
                <img src="{{ asset('icons/white/back-white.png') }}">
            </a>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let backButton = document.querySelector('.header-btn.left-btn');
                    if (backButton) {
                        backButton.addEventListener('click', function(event) {
                            event.preventDefault();
                            let preReportReferrer = sessionStorage.getItem('preReportReferrer');
                            let previousPage = document.referrer;

                            if (previousPage.includes('/report')) {
                                if (preReportReferrer) {
                                    window.location.href = preReportReferrer;
                                } else {
                                    window.location.href = '/map'; // Изменено на переход к карте
                                }
                                sessionStorage.removeItem('preReportReferrer');
                            } else if (previousPage.includes('/moderation')) {
                                window.location.href = '/map';
                            } else {
                                window.history.back();
                            }
                        });
                    }
                });
            </script>
            <h2 style="margin: 12px 0px;">Пожаловаться на арт "{{ $art->id }}"</h2>
            <a class="header-btn right-btn" style="cursor:default"></a>
    </div>
    <div class="content">
        <div class="container">
            <form action="{{ route('reports.store', $art) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="reason" style="font-size: 20px">Причина жалобы:</label>
                    <p>
                    <textarea name="reason" id="reason" class="form-control form-text" rows="5" required></textarea>
                </div>
                <button type="submit" class="form-btn main-btn" style="width: 300px">Отправить жалобу</button>
            </form>
        </div>
    </div>
@endsection