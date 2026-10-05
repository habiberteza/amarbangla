@extends('errors.layout')

@section('title', 'সার্ভারে সাময়িক সমস্যা হয়েছে (Server Error)')
@section('code', '500')
@section('message')
    সার্ভারে একটি অপ্রত্যাশিত ত্রুটি দেখা দিয়েছে। আমাদের কারিগরি দল বিষয়টি সমাধান করতে কাজ করছে। অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।
@endsection
