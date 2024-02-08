@extends('layouts.master')

@section('content')
    <h1>Limitele bugetelor pe categorie</h1>
    <a href="{{ route('pages.monthly.create') }}" class="btn btn-primary mb-2">Adauga o limita</a>

    <div class="row">
        @php
            // Inițializează suma totală a cheltuielilor
            $totalExpenses = 0;

            // Inițializează un array pentru a ține suma totală și limita lunară pentru fiecare categorie
            $totalExpensesAndMonthlyLimitsByCategory = [];

            // Calculează suma totală și limita lunară a cheltuielilor pentru fiecare categorie
            foreach ($expenses as $expenseItem) {
                $totalExpenses += $expenseItem->Amount;

                if (!isset($totalExpensesAndMonthlyLimitsByCategory[$expenseItem->category_id])) {
                    $totalExpensesAndMonthlyLimitsByCategory[$expenseItem->category_id] = [
                        'totalExpense' => 0,
                        'monthlyLimit' => null,
                    ];
                }
                $totalExpensesAndMonthlyLimitsByCategory[$expenseItem->category_id]['totalExpense'] += $expenseItem->Amount;
            }

            // Setează limita lunară pentru fiecare categorie
            foreach ($monthlyBudgets as $monthlyBudget) {
                if (isset($totalExpensesAndMonthlyLimitsByCategory[$monthlyBudget->category_id])) {
                    $totalExpensesAndMonthlyLimitsByCategory[$monthlyBudget->category_id]['monthlyLimit'] = $monthlyBudget->Amount;
                }
            }
        @endphp

        {{-- Afișează suma totală a cheltuielilor --}}
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Suma totala lunară:</h5>
                    <p class="card-text">{{ $totalExpenses }} <b>RON</b></p>
                </div>
            </div>
        </div>

        {{-- Afiseaza suma totala și limita lunară pentru fiecare categorie --}}
        @foreach ($totalExpensesAndMonthlyLimitsByCategory as $categoryId => $data)
            @php
                // Obține categoria pentru cheltuială
                $category = \App\Models\ExpenseCategory::find($categoryId);
                // Verifică dacă suma totală depășește limita lunară
                $exceededLimit = $data['monthlyLimit'] !== null && $data['totalExpense'] > $data['monthlyLimit'];
            @endphp

            <div class="col-md-4 mb-3">
                <div class="card{{ $exceededLimit ? ' border-danger' : '' }}">
                    <div class="card-body">
                        <h5 class="card-title">Categoria: {{ $category->Name }}</h5>
                        {{-- Afișează suma totală pentru categoria curentă --}}
                        <p class="card-text">A-ti cheltuit deja: {{ $data['totalExpense'] }}</p>
                        {{-- Afișează limita lunară pentru categoria curentă --}}
                        <p class="card-text{{ $exceededLimit ? ' text-danger' : '' }}">Limita lunara: {{ $data['monthlyLimit'] ?? 'Nu exista limita lunara' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
