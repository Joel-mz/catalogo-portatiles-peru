@extends('layouts.admin')

@section('header_title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-slate-800">Mi Perfil</h2>
        <p class="text-sm text-slate-500">Gestiona los datos de tu cuenta y credenciales de acceso.</p>
    </div>

    @if (session('status') === 'admin-two-factor-required')
        <div role="alert" class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900">
            Por seguridad, activa la autenticación en dos pasos para acceder al panel administrador. Configúrala en la sección de abajo.
        </div>
    @endif

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
            @include('profile.partials.two-factor-authentication-form')
        </div>
    </div>

    <div class="soft-card p-6">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
