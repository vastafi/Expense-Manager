@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        Lista de finante
                        <a href="{{ route('pages.expense.create') }}" class="btn btn-sm btn-dark float-right">Adauga in lista cheltuiala</a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Categorie</th>
                                <th>Suma</th>
                                <th>Data</th>
                                <th>Descriere</th>
                                <th>Modificare</th>
                                <th>Stergere</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    @php
                                        // Obține categoria pentru fiecare cheltuială
                                        $categories = \App\Models\ExpenseCategory::find($expense->category_id);
                                    @endphp
                                    <td>{{ $expense->id }}</td>
                                    <td>{{ $categories -> Name }}</td> <!-- Afiseaza numele categoriei -->
                                    <td>{{ $expense->Amount }}</td>
                                    <td>{{ $expense->Date }}</td>
                                    <td>{{ $expense->Description}}</td>
                                    <td>
                                        <!-- Buton pentru editare -->
                                        <a href="{{ route('pages.expense.edit', $expense->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-solid fa-edit"></i></a>
                                    </td>
                                <td>
                                    <!-- Formular pentru ștergere -->
                                    <form action="{{ route('pages.expense.destroy', $expense->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ești sigur că vrei să ștergi această înregistrare?')"><i class="fas fa-solid fa-trash"></i> Șterge</button>
                                    </form>
                                </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection
