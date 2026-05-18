@extends('layouts.app')

@section('content')
    @include('sections.hero')
    @include('sections.features')
    @include('sections.retail')
    @include('sections.markets')
@endsection

@push('scripts')
    <script src="{{ asset('js/home-script.js') }}"></script>
@endpush
