@extends('layouts.admin')

@section('header_title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Mi Perfil</h2>
        <p class="text-sm text-slate-500">Gestiona los datos de tu cuenta y credenciales de acceso.</p>
    </div>

    <div class="soft-card p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="soft-card p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="soft-card p-6">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
