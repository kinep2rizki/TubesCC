@extends('ErrorHandling.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('message', $exception->getMessage() ?: 'Oops! The page you are looking for does not exist or has been moved.')

@section('icon', 'search_off')
@section('icon_bg_class', 'bg-surface-variant')
@section('icon_text_class', 'text-on-surface-variant')
