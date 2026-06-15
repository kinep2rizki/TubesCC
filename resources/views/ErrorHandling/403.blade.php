@extends('ErrorHandling.layout')

@section('title', 'Access Denied')
@section('code', '403')
@section('message', $exception->getMessage() ?: 'Access Denied / Forbidden')

@section('icon', 'lock')
@section('icon_bg_class', 'bg-warning-container')
@section('icon_text_class', 'text-on-warning-container')
