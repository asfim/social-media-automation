@extends('layouts.app')

@section('title', 'New Leads')

@section('content')
@include('leads._table', ['heading' => 'New Leads'])
@endsection
