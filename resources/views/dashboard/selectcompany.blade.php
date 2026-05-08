<h2>Select Company</h2>

<form method="POST" action="/change-company">
    @csrf

    <select name="company_id">
        @foreach($companies as $company)
            <option value="{{ $company->id }}">
                {{ $company->name }} ({{ $company->pivot->role }})
            </option>
        @endforeach
    </select>

    <button type="submit">Continue</button>
</form>