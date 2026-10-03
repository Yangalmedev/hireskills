@extends('dashboard.layout')
@section('title', 'Admin Dashboard')
@section('nav')
    <a class="active" href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('home') }}">Home</a>
@endsection
@section('content')
    <h1>Admin dashboard</h1>
    <p class="sub">Overview of everyone on HireSkills.</p>

    <div class="grid g3">
        <div class="card"><h3>Total users</h3><p style="font-size:36px;font-weight:700;margin:0">{{ $stats['total'] }}</p></div>
        <div class="card"><h3>Freelancers</h3><p style="font-size:36px;font-weight:700;margin:0">{{ $stats['freelancers'] }}</p></div>
        <div class="card"><h3>Employers</h3><p style="font-size:36px;font-weight:700;margin:0">{{ $stats['employers'] }}</p></div>
    </div>

    <div class="card" style="margin-top:22px;overflow-x:auto">
        <h3>Users</h3>
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
            <tbody>
            @foreach($users as $u)
                <tr><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td><span class="tag">{{ $u->role }}</span></td><td>{{ $u->created_at->format('M d, Y') }}</td></tr>
            @endforeach
            </tbody>
        </table>
        <div class="pager">{{ $users->links() }}</div>
    </div>
@endsection
