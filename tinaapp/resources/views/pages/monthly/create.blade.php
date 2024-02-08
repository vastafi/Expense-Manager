@extends('layouts.master')

@section('content')
    <h1>Creează bugetul lunar</h1>
    <form action="{{ route('pages.monthly.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Utilizator</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category_id">Categoria</label>
            <select name="category_id" id="category_id" class="form-control">
                @foreach(\App\Models\ExpenseCategory::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->Name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Amount">Monthly Limit</label>
            <input type="text" class="form-control" id="Amount" name="Amount">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
