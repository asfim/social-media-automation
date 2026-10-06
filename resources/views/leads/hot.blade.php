@extends('layouts.app')

@section('title', 'Hot Leads')

@section('content')
@include('leads._table', ['heading' => 'Hot Leads'])
@endsection
