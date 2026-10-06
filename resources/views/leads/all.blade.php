@extends('layouts.app')

@section('title', 'All Leads')

@section('content')
@include('leads._table', ['heading' => 'All Leads'])
@endsection
