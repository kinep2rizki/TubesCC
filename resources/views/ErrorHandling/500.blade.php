@extends('ErrorHandling.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message', 'Oops! Something went wrong on our end. We are looking into it.')

@section('icon', 'error')
@section('icon_bg_class', 'bg-error-container')
@section('icon_text_class', 'text-on-error-container')
