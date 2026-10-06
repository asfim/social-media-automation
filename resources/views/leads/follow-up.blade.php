@extends('layouts.app')

@section('title', 'Follow Up')

@section('content')
@include('leads._table', ['heading' => 'Follow Up'])
@endsection
