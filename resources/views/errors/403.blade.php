@extends('errors::minimal')

@section('title', __('CO2 - Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
