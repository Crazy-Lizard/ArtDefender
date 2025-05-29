@extends('layouts.main-layout')

@section('content')
    <div class="container">
        <h2>Пожаловаться на арт "{{ $art->id }}"</h2>
        <form action="{{ route('reports.store', $art) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="reason">Причина жалобы:</label>
                <textarea name="reason" id="reason" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="form-btn main-btn" style="width: 300px">Отправить жалобу</button>
        </form>
    </div>
@endsection